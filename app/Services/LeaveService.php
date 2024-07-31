<?php

namespace App\Services;

use App\Models\Compensatory;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\User;
use App\Notifications\LeaveAppliedNotification;
use App\Notifications\LeaveApprovedNotification;
use App\Notifications\LeaveCancelledNotification;
use App\Notifications\LeaveRejectedNotification;
use App\Repository\LeaveRepository;
use App\Traits\GeneralTrait;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Notification;

class LeaveService
{
    use GeneralTrait;

    protected $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }

    public function getpendingApplications($date)
    {
        return Leave::whereRaw('YEAR(from_date) ='.$date)->where('user_id', $this->getCurrentUserId())->where('status', 'Waiting')->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Function to get all user apllications.
     *
     * @param  int  $userId  User Id
     * @return Leave object
     */
    public function getUserLeaveApplications($userId = null, $date = null)
    {
        $userLeaveApplications = Leave::with('user_approved')->where('status', '!=', 'Waiting');

        if ($date) {
            $userLeaveApplications->whereRaw('YEAR(from_date) ='.$date);
        }

        if ($userId) {
            $userLeaveApplications->where('user_id', $userId);
        }

        return $userLeaveApplications->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Function to get leaves listing by role.
     *
     * @param  string  $role  Role of logged in user
     * @return mixed[]
     */
    public function getLeaveListingByRole($role)
    {
        $date = request('date');
        $userId = auth()->user()->id;
        $allLeaves = $this->getAllLeaves($userId, $date);
        $balance = $this->getUserLeaveBalanceCount($userId);
        $content = view('leave.list', compact('date', 'allLeaves', 'balance'))->render();

        return $content;
    }

    public function pendingApplications()
    {
        return Leave::with('users')->where('status', 'Waiting')->orderBy('created_at', 'DESC')->get();
    }

    public function getHolidayList($fromDate)
    {
        return Holiday::whereRaw('YEAR(holiday_date) = '.Carbon::parse($fromDate)->year)->pluck('holiday_date')->toArray();
    }

    /**
     * Function to get total leave days count without weekendds.
     */
    public function getLeaveDaysCount($fromDate, $toDate, $session)
    {
        $weekends = [];
        $holidays = [];
        $leaveDaysPeriod = CarbonPeriod::create($fromDate, $toDate);

        $days = 0.5;

        if ($session == 'Full Day') {
            $days = 1;
        }
        $holidayList = $this->getHolidayList($fromDate);
        $holidayList = array_map(function ($date) {
            return Carbon::parse($date);
        }, $holidayList);

        // Iterate over the date period
        foreach ($leaveDaysPeriod as $date) {
            if ($date->isWeekend()) {
                array_push($weekends, $date);
            }
            if (in_array($date, $holidayList)) {
                array_push($holidays, $date);
            }
        }

        $leaveDaysCountWithoutWeekend = count($leaveDaysPeriod) - count($weekends) - count($holidays);

        return $leaveDaysCountWithoutWeekend * $days;
    }

    public function getCompensatoryDaysCount($offs)
    {
        $count = 0;

        foreach ($offs as $off) {
            if ($off->session == 'Full Day') {
                $count += 1;
            } else {
                $count += 0.5;
            }
        }

        return $count;
    }

    public function getApprovedUserLeaves($userId)
    {
        return Leave::where('user_id', $userId)->whereIn('status', ['Waiting', 'Approved'])->whereRaw('YEAR(created_at) ='.date('Y'));
    }

    /**
     * Function to get total leave balance count.
     *
     * @param  int  $userId  User Id
     * @return mixed[] $balance Array of leave balance count
     */
    public function getUserLeaveBalanceCount($userId, $id = null)
    {
        $balance = $this->getInitialLeavesCount($userId);
        $approvedUserleaves = $this->getApprovedUserLeaves($userId);

        if ($id) {
            $approvedUserleaves = $approvedUserleaves->where('id', '!=', $id);
        }

        $approvedUserleaves = $approvedUserleaves->get();

        $total_taken_leaves = 0;
        foreach ($approvedUserleaves as $leave) {
            $leaveDaysCount = $this->getLeaveDaysCount($leave->from_date, $leave->to_date, $leave->session);
            $total_taken_leaves += $leaveDaysCount;
            if ($leave->type == 'Casual') {
                $balance['casual'] -= $leaveDaysCount;
            } elseif ($leave->type == 'Medical') {
                $balance['medical'] -= $leaveDaysCount;
            } elseif ($leave->type == 'LOP') {
                $balance['lop'] += $leaveDaysCount;
            } elseif ($leave->type == 'Compensatory') {
                $balance['compensatory'] += $leaveDaysCount;
            } elseif ($leave->type == 'Paternity') {
                $balance['paternity'] -= $leaveDaysCount;
            }
        }
        $balance['total_taken_leaves'] = $total_taken_leaves;
        $balance['compensatory_available'] = $this->getCompensatoryDaysCount(Compensatory::where('status', 'Approved')->where('user_id', $userId)->whereRaw('YEAR(date) ='.date('Y'))->get()) - $balance['compensatory'];

        return $balance;
    }

    public function getUserLeave($userId, $fromDate, $toDate)
    {
        return Leave::where('user_id', $userId)
            ->where(function ($query) use ($fromDate, $toDate) {
                $query->whereDate('from_date', '<=', $fromDate)
                    ->WhereDate('to_date', '>=', $toDate);
            })->whereIn('status', ['Waiting', 'Approved']);
    }

    /**
     * Function to check whether the user is already applied leave for the same date.
     *
     * @param  int  $userId  User Id
     * @param  string  $fromDate  Leave application from date
     * @param  string  $toDate  Leave application to date
     * @return bool
     */
    public function isAlreadyAppliedIntheDate($userId, $fromDate, $toDate, $id = null, $leaveSession = 'Full Day')
    {
        $userLeave = $this->getUserLeave($userId, $fromDate, $toDate);

        if ($id) {
            $userLeave = $userLeave->where('id', '!=', $id);
        }

        $userLeave = $userLeave->get();

        if ($userLeave->count()) {
            $sessionExist = false;

            foreach ($userLeave as $leave) {
                $leaveDaysCount = $this->getLeaveDaysCount($leave->from_date, $leave->to_date, $leave->session);

                if (fmod($leaveDaysCount, 1.0) == 0.5) {
                    $sessionExist = true;
                }
                if ($leave->session == 'Full Day' || $leave->session == $leaveSession) {
                    return 1;
                }
            }

            return $sessionExist ? 2 : 1;
        }

        return false;
    }

    /**
     * Function to get leaves listing by role.
     *
     * @param  string  $role  Role of logged in user
     * @return mixed[]
     */
    public function getLeaveListings($date, $type = null)
    {
        $user = $this->getCurrentUser();

        if ($user->can('manage-leave')) {
            $userId = null;
        } else {
            $userId = $user->id;
        }

        return $this->getAllLeaves($userId, $date, $type);
    }

    public function getLeaveWithUsers()
    {
        return Leave::with('users')->with('user_approved');
    }

    public function getAllLeaves($userId = null, $date = null, $type = null)
    {
        $leaves = $this->getLeaveWithUsers();

        if ($date) {
            $leaves->whereRaw('YEAR(from_date) ='.$date);
        }

        if ($userId) {
            $leaves->where('user_id', $userId);
        }

        if ($type) {
            if ($type == 'pending') {
                $leaves->where('status', 'Waiting');
            } else {
                $leaves->where('status', '!=', 'Waiting');
            }
        }

        return $leaves->orderBy('from_date', 'ASC')->get();
    }

    public function getPreviousApplications($userId, $year = null, $month = null, $userType = null, $leaveType = null)
    {
        $applications = [];
        $allApplications = $this->getPreviousApplicationsList($userId, $year, $month, $userType, $leaveType);

        $allApplications = $allApplications->groupBy('user_id');

        foreach ($allApplications as $user_id => $allapplication) {
            $applications[$user_id]['employee'] = $allapplication[0]->users->full_name;
            $applications[$user_id]['employee_id'] = $allapplication[0]->users->employee_id;
            foreach ($allapplication as $application) {
                $period = Carbon::parse($application->from_date)->daysUntil($application->to_date);

                // Iterate over the period
                foreach ($period as $date) {
                    $applications[$user_id]['dates'][$date->format('Y-m-d')] = $application->status == 'Approved' ? $application->session.' '.$application->status : $application->session.' '.$application->status;
                }
            }
        }

        return $applications;
    }

    public function getPreviousApplicationsList($userId, $year = null, $month = null, $userType = null, $leaveType = null)
    {
        $leaves = $this->getLeaveWithUsers();

        if ($year) {
            $leaves->where(function ($query) use ($year) {
                $query->whereRaw('YEAR(from_date) ='.$year)->orWhereRaw('YEAR(to_date) ='.$year);
            });
        }

        if ($year) {
            $leaves->where(function ($query) use ($month) {
                $query->whereRaw('MONTH(from_date) ='.$month)->orWhereRaw('MONTH(to_date) ='.$month);
            });
        }

        if ($userId) {
            $leaves->where('user_id', $userId);
        }

        if ($userType != '' && ($userType == 0 || $userType == 1)) {
            $leaves->whereHas('users', function ($query) use ($userType) {
                $query->where('contract', $userType);
            });
        }

        if ($leaveType) {
            $leaves->where('type', $leaveType);
        }

        $leaves->where('status', '!=', 'Waiting');

        $allApplications = $leaves->orderBy('from_date', 'ASC')->get();

        return $allApplications;
    }

    public function sendLeaveAppliedNotification($leave)
    {
        $users = User::leaveAdmins()->get();

        foreach ($users as $key => $user) {
            Notification::send($user, new LeaveAppliedNotification($user, $leave));
        }
    }

    public function sendLeaveCancelledNotification($leave)
    {
        $users = User::leaveAdmins()->get();
        foreach ($users as $key => $user) {
            Notification::send($user, new LeaveCancelledNotification($user, $leave));
        }
    }

    public function sendLeaveApprovedNotification($leave)
    {
        $users = User::leaveAdmins()->get();
        $users = $users->push($leave->users);
        foreach ($users as $key => $user) {
            Notification::send($user, new LeaveApprovedNotification($user, $leave));
        }
    }

    public function sendLeaveRejectedNotification($leave)
    {
        $users = User::leaveAdmins()->get();
        $users = $users->push($leave->users);
        foreach ($users as $key => $user) {
            Notification::send($user, new LeaveRejectedNotification($user, $leave));
        }
    }

    public function getUserCompensatoryDaysCount($userId)
    {
        $totalCompensatoryApplied = $this->getCompensatoryDaysCount(Compensatory::where('status', 'Approved')->where('user_id', $userId)->whereRaw('YEAR(date) ='.date('Y'))->get());
        $totalCompensatoryTaken = $this->getCompensatoryDaysCount(Leave::where('type', 'Compensatory')->where('status', 'Approved')->where('user_id', $userId)->whereRaw('YEAR(created_at) ='.date('Y'))->get());

        return $totalCompensatoryApplied - $totalCompensatoryTaken;
    }

    public function getInitialLeavesCount($userId)
    {
        $leaves = config('leaves');
        $user = $this->getUserByid($userId);
        $userJoiningDate = $user->joining_date ?? Carbon::now()->subYear();
        $userJoiningDate = Carbon::parse($userJoiningDate);

        $balance = [
            'lop' => 0,
            'compensatory' => 0,
            'total_taken_leaves' => 0,
            'compensatory_available' => 0
        ];

        if ($userJoiningDate->year === Carbon::now()->year) {
            $balance['casual'] = $leaves['Casual'] - (int) $userJoiningDate->month + 1;
            $remainingMonths = 12 - (int) $userJoiningDate->month + 1;
            $balance['medical'] = ($remainingMonths % 2 == 0) ?
                (floatval($leaves['Medical'] / 12)) * $remainingMonths :
                (int) (floatval($leaves['Medical'] / 12) * $remainingMonths) + 1;
        } else {
            $balance['casual'] = $leaves['Casual'];
            $balance['medical'] = $leaves['Medical'];
        }

        if ($user->gender == 'Male') {
            $balance += ['paternity' => $leaves['paternity']];
        }

        return $balance;
    }

    public function getFromDate()
    {
        return Carbon::createFromFormat('d/m/Y', request('from_date'))->format('Y-m-d');
    }

    public function getToDate()
    {
        return Carbon::createFromFormat('d/m/Y', request('to_date'))->format('Y-m-d');
    }

    public function store($request)
    {
        $from_date = $this->getFromDate();
        $to_date = $this->getToDate();
        $userId = $request->user_id ?? $this->getCurrentUserId();
        $status = $request->status ?? 'Waiting';
        $leaveType = request('type');

        $leaveDaysCount = $this->getLeaveDaysCount($from_date, $to_date, request('session'));
        $leaveBalance = $this->getUserLeaveBalanceCount($userId);
        $data = [
            'user_id' => $userId,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'type' => $leaveType,
            'session' => request('session'),
            'lop' => request('type') == 'LOP' ? 'Yes' : 'No',
            'reason' => strip_tags(request('reason')),
            'status' => $status,
            'email_code' => bin2hex(openssl_random_pseudo_bytes(8))
        ];

        if ($data['status'] == 'Approved') {
            $data['approved_by'] = $this->getCurrentUserId();
        }

        try {
            // check wheather the applied leave count is greater than leave balance
            if (($leaveType != 'Compensatory' && $leaveType != 'LOP' && $leaveType != 'Maternity') && $leaveDaysCount > $leaveBalance[strtolower($leaveType)]) {
                throw  ValidationException::withMessages(['to_date' => 'Leave balance is '.$leaveBalance[strtolower($leaveType)].
                ' for type <i>'.$leaveType.'</i>. Please enter a lower date range!!']);
            }
            if ($leaveType == 'Compensatory' && $leaveDaysCount > $this->getUserCompensatoryDaysCount($userId)) {
                throw  ValidationException::withMessages(['to_date' => 'Leave balance is '.$this->getUserCompensatoryDaysCount($userId).
                ' for type <i>'.$leaveType.'</i>. Please enter a valid range!!']);
            }

            $leaveSession = request('session');

            $isExist = LeaveService::isAlreadyAppliedIntheDate($userId, $from_date, $to_date, null, $leaveSession);
            if ($isExist) {
                if ($isExist == 2) {
                    throw  ValidationException::withMessages(['to_date' => 'Already applied leave for this date. Cancel and re-apply for this session']);
                }
                throw  ValidationException::withMessages(['to_date' => 'Already applied leave for this date']);
            }
            $leave = $this->createLeave($data);

            $this->sendLeaveAppliedNotification($leave);

            $diff = Carbon::parse($data['from_date'])->diffInDays(Carbon::parse(date('Y-m-d')));

            if ($userId == $this->getCurrentUserId() && $diff < config('app.leave_apply_before')) {
                return response()->json(['flag' => false, 'message' => 'Please apply for leave before '.config('app.leave_apply_before').'days next time onwards.']);
            }

            return response()->json(['flag' => true, 'message' => 'Leave Applied successfully']);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'The given data was invalid',
                'errors' => $e->errors()], 422);
        }
    }

    public function acceptLeave()
    {
        return $this->leaveRepository->acceptLeave();
    }

    public function rejectLeave()
    {
        return $this->leaveRepository->rejectLeave();
    }

    public function getUsers()
    {
        return $this->leaveRepository->getUsers();
    }

    public function getHolidays($request)
    {
        return Holiday::whereRaw('YEAR(holiday_date) = '.$request->year)->pluck('holiday_date')->toArray();
    }

    public function getStart($request)
    {
        return Carbon::parse($request->year.'-'.$request->month)->startOfMonth();
    }

    public function getEnd($request)
    {
        return Carbon::parse($request->year.'-'.$request->month)->endOfMonth();
    }

    public function getDates($request)
    {
        $start = $this->getStart($request);
        $end = $this->getEnd($request);

        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->format('Y-m-d');
            $start->addDay();
        }

        return $dates;
    }

    public function getBalance($leaves)
    {
        return ['casual' => $leaves['Casual'], 'medical' => $leaves['Medical'], 'lop' => 0, 'compensatory' => 0, 'total_taken_leaves' => 0, 'compensatory_available' => 0];
    }

    public function update($id, $request)
    {
        $leave = $this->findLeaveById($id);
        $from_date = $this->getFromDate();
        $to_date = $this->getToDate();
        $status = $request->status;
        $leaveType = request('type');
        $userId = request('user_id');
        $leaveDaysCount = $this->getLeaveDaysCount($from_date, $to_date, request('session'));
        $leaveBalance = $this->getUserLeaveBalanceCount($userId, $id);

        $data = [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'type' => $leaveType,
            'session' => request('session'),
            'lop' => request('type') == 'LOP' ? 'Yes' : 'No',
            'reason' => strip_tags(request('reason')),
            'reason_for_rejection' => request('reason_for_rejection'),
            'status' => $status,
        ];

        if ($leave->status != 'Approved' && $data['status'] == 'Approved') {
            $data['approved_by'] = $this->getCurrentUserId();
        }

        try {
            // check wheather the applied leave count is greater than leave balance
            if (($leaveType != 'Compensatory' && $leaveType != 'LOP') && $leaveDaysCount > $leaveBalance[strtolower($leaveType)]) {
                throw  ValidationException::withMessages(['to_date' => 'Leave balance is '.$leaveBalance[strtolower($leaveType)].
                'for type <i>'.$leaveType.'</i>. Please enter a lower date range!!']);
            }
            $leaveSession = request('session');
            if (LeaveService::isAlreadyAppliedIntheDate($userId, $from_date, $to_date, $id, $leaveSession)) {
                throw  ValidationException::withMessages(['to_date' => 'Already applied leave for this date']);
            }

            $leave->update($data);

            //$diff = Carbon::parse($data['from_date'])->diffInDays(Carbon::parse(date('Y-m-d')));

            return response()->json(['flag' => true, 'message' => 'Leave updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'The given data was invalid',
                'errors' => $e->errors()], 422);
        }
    }

    public function getLeaveTypes()
    {
        return $this->leaveRepository->getLeaveTypes();
    }
}
