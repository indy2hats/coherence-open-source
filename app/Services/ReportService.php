<?php

namespace App\Services;

use App\Models\Client;
use App\Models\User;
use App\Repository\ReportRepository;
use App\Repository\UserRepository;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use DataTables;
use Exception;
use Illuminate\Support\Facades\Log;

class ReportService
{
    use GeneralTrait{
        GeneralTrait::createDailyStatusReport as traitCreateDailyStatusReport;
    }

    protected $userRepository;
    protected $leaveService;
    protected $reportRepository;

    public function __construct(UserRepository $userRepository, LeaveService $leaveService, ReportRepository $reportRepository)
    {
        $this->userRepository = $userRepository;
        $this->leaveService = $leaveService;
        $this->reportRepository = $reportRepository;
    }

    public function getSessionsForDSR()
    {
        $date = Carbon::now()->format('Y-m-d');
        if (auth()->user()->dsr_late_date != null) {
            $date = self::getCurrentUser()->dsr_late_date;
        }

        $taskSession = $this->reportRepository->getTaskSession($date);

        $content = '';
        if (! $taskSession->isEmpty()) {
            foreach ($taskSession as $eachSession) {
                $taskCode = $eachSession->task->task_id ?? $eachSession->task->code;
                $content .= $taskCode.'  '.trim($eachSession->task->project->project_name).' / '.trim($eachSession->task->title)."\n";
                $content .= (trim($eachSession->comments) != null) ? trim($eachSession->comments)."\n\n" : "\n";
            }
        }

        return trim($content);
    }

    /**
     * get total count of user leaves - to show record count in datatable.
     *
     * @return int
     */
    public function getUsersLeavesCount()
    {
        return $this->userRepository->getUsersWithLeaves()->count();
    }

    /**
     * get to count of user leaves with filter applied - to show record count in datatable.
     *
     * @param  mixed  $filter
     * @return int
     */
    public function getUsersLeavesWithFilterCount($request)
    {
        $filter = $request['filter'];

        return $this->filterUsersLeaves($filter)->count();
    }

    /**
     * get user leaves with / without filter applied.
     *
     * @param  mixed  $request
     * @return array
     */
    public function getUsersLeaves($request)
    {
        $offset = $request->get('start') ?? 0;
        $limit = $request->get('length') ?? 25;
        $columns = [
            0 => 'first_name',
            1 => 'totalPaidLeaves',
            2 => 'totalLops',
            3 => 'totalLeaves',
        ];
        $sortColumn = $columns[$request->input('order.0.column')];
        $sort = $request->input('order.0.dir') == 'desc' ? 'sortByDesc' : 'sortBy';

        $userLeaves = [];
        try {
            $userLeaves = $this->filterUsersLeaves($request['filter'])
                                ->$sort($sortColumn)
                                ->skip($offset)
                                ->take($limit);
        } catch (Exception $e) {
            Log::error('Error while getting filtered leaves : '.$e->getMessage());
        }

        return $userLeaves;
    }

    /**
     * filter user leaves.
     *
     * @param  mixed  $filter
     */
    public function filterUsersLeaves($filter)
    {
        $users = $this->userRepository->getUsersWithLeaves();
        if (isset($filter['year'])) {
            $dateFilter = $filter['year'].'-';
            if (isset($filter['month']) && $filter['month'] != '') {
                $dateFilter = $dateFilter.$filter['month'].'-';
            }
            $users = $this->userRepository->filterLeavesByMonthYear($users, $dateFilter);
        }
        if (isset($filter['user_id']) && $filter['user_id'] != '') {
            $users = $this->userRepository->filterLeavesByFieldName($users, 'id', $filter['user_id']);
        }

        $users = $this->userRepository->getUsersLeavesAndLopCount($users->get(), $filter);

        return $users;
    }

    /**
     * formats the list in data table format.
     *
     * @param  mixed  $users
     * @return array
     */
    public function formatUsersLeaveList($users)
    {
        $dataArray = [];
        if (! $users) {
            return $dataArray;
        }
        foreach ($users as $user) {
            $dataArray[] = [
                'employeeName' => $user->first_name.' '.$user->last_name,
                'totalPaidLeaves' => $user->totalPaidLeaves,
                'totalLops' => $user->totalLops,
                'totalLeaves' => $user->totalLeaves,
            ];
        }

        return $dataArray;
    }

    public function getClientsForClientsCountWithoutfilter()
    {
        return Client::select('id', 'company_name')->get();
    }

    public function getProjects($request)
    {
        $daterange = $request->daterange;

        $fromDate = null;
        $toDate = null;

        if ($daterange != '') {
            $daterange = explode(' - ', $daterange);
            $fromDate = $this->getFromDate($daterange);
            $toDate = $this->getToDate($daterange);
        }

        return $this->reportRepository->getProjects($fromDate, $toDate);
    }

    public function getTasks($id)
    {
        return $this->reportRepository->getTasks($id);
    }

    public function getUsers($daterange)
    {
        $fromDate = null;
        $toDate = null;

        if ($daterange != '') {
            $daterange = explode(' - ', $daterange);
            $fromDate = $this->getFromDate($daterange);
            $toDate = $this->getToDate($daterange);
        }

        return $this->reportRepository->getUsers($fromDate, $toDate);
    }

    public function getClients($daterange)
    {
        $fromDate = null;
        $toDate = null;

        if ($daterange != '') {
            $daterange = explode(' - ', $daterange);
            $fromDate = $this->getFromDate($daterange);
            $toDate = $this->getToDate($daterange);
        }

        return $this->reportRepository->getClients($fromDate, $toDate);
    }

    public function getUsersForEmployeePerformanceSearch($fromDate, $toDate)
    {
        return $this->reportRepository->getUsersForEmployeePerformanceSearch($fromDate, $toDate);
    }

    public function getWorkingDays($fromDate, $toDate)
    {
        $businessDays = new BusinessDays();
        $holidays = $this->reportRepository->getHolidays($fromDate, $toDate);

        if ($holidays->count() > 0) {
            $holidayArray = $holidays->pluck('holiday_date')->toArray();
            $businessDays->addHolidays($holidayArray);
        }

        return $businessDays->daysBetween(Carbon::parse($fromDate), Carbon::parse($toDate));
    }

    public function getDateForDailyStatusReport()
    {
        $businessDays = new BusinessDays();

        $holidays = $this->getHolidays();

        if ($holidays->count() > 0) {
            $holidayArray = $holidays->pluck('holiday_date')->toArray();
            $businessDays->addHolidays($holidayArray);
        }

        return $businessDays->getLastWorkingDay($this->lastDay())->format('d/m/Y');
    }

    public function getDailyReports($date)
    {
        return $this->reportRepository->getDailyReports($date);
    }

    public function createDailyStatusReport($request)
    {
        $input = $request->except('_token');
        $input['user_id'] = $this->getCurrentUserId();

        $this->traitCreateDailyStatusReport($input);
    }

    public function getDateForDailyStatusReportSearch($request)
    {
        $dateRange = null;
        if ($request->has('daterange')) {
            $dateRange = $request->daterange;
        }

        $date = $this->getDate();
        if ($dateRange) {
            $date = Carbon::createFromFormat('d/m/Y', trim($dateRange))->format('Y-m-d');
        }

        return $date;
    }

    public function getDate()
    {
        return Carbon::now()->format('Y-m-d');
    }

    public function isWeekend($date)
    {
        $weekend = false;
        if (Carbon::parse($date)->isWeekend()) {
            $weekend = true;
        }

        return $weekend;
    }

    public function updateUserDetails($request)
    {
        $input = $request->except('_token');
        $input['user_id'] = $this->getCurrentUserId();
        $this->traitCreateDailyStatusReport($input);

        $user = $this->getUserById($input['user_id']);
        $user->dsr_late_notify = 0;
        $user->dsr_late_date = null;
        $user->save();
    }

    public function userAccountReport($users, $status, $search)
    {
        return  Datatables::of($users)
                    ->filter(function ($instance) use ($status, $search) {
                        if ($status == '0') {
                            $instance->where('google2fa_secret', null);
                        }
                        if ($status == '1') {
                            $instance->where('google2fa_secret', '<>', null);
                        }
                        if (! empty($search)) {
                            $instance->where(function ($sql) use ($search) {
                                $sql->orWhere('first_name', 'LIKE', "%$search%")
                                    ->orWhere('last_name', 'LIKE', "%$search%")
                                    ->orWhere(function ($query) use ($search) {
                                        return $query->whereHas('role', function ($query) use ($search) {
                                            return $query->where('display_name', 'LIKE', "%$search%");
                                        });
                                    });
                            });
                        }
                    })->addColumn('status', function ($row) {
                        if ($row->google2fa_secret) {
                            return '<span class="fa fa-check fa-ul text-green"></span>';
                        } else {
                            return '<span class="fa fa-times fa-ul text-danger"></span>';
                        }
                    })
                    ->addColumn('role', function ($row) {
                        return $row->role->display_name;
                    })
                    ->addColumn('name', function ($row) {
                        return $row->fullname;
                    })
                    ->addColumn('action', function ($row) {
                        if ($row->google2fa_secret) {
                            return '<span><a href="#" class="btn btn-xs btn-danger disable_user_2fa" data-id="'.$row->id.'"><i class="fa fa-times"></i> Disable Two Factor</a></span>';
                        }

                        return null;
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
    }

    public function getDateForReport()
    {
        $firstDay = $this->firstDay();
        $lastDay = $this->lastDay();

        return $firstDay->format('d/m/Y').' - '.$lastDay->format('d/m/Y');
    }

    public function getDateForTaskBounceIndex()
    {
        $firstDay = $this->firstDay();
        $lastDay = $firstDay->copy()->endOfMonth();

        return $firstDay->format('d/m/Y').' - '.$lastDay->format('d/m/Y');
    }

    public function getTasksForTaskBounceReport($request)
    {
        $dateRange = $request->daterange;
        $userId = $request->userId;
        $projectId = $request->projectId;
        $severity = $request->severity;

        $tasks = $this->reportRepository->getTaskRejections();
        if ($dateRange != '') {
            $daterange = explode(' - ', $dateRange);
            $from_date = $this->getFromDate($daterange);
            $to_date = $this->getToDate($daterange);
            $tasks = $tasks->whereDate('updated_at', '>=', $from_date)->whereDate('updated_at', '<=', $to_date);
        }

        $tasks = $this->reportRepository->getTasksForTaskBounceReport($tasks, $userId, $projectId, $severity);

        if ($tasks) {
            $qaIssues = $this->getAllQaIssues();
            foreach ($tasks as $key => $rejectedTask) {
                $qaIssuesList = $rejectedTask->reason;
                $issueList = explode('_', $qaIssuesList);
                $rejectReasons = [];
                foreach ($qaIssues as $issue) {
                    if (in_array($issue->id, $issueList)) {
                        $rejectReasons[] = $issue->title;
                    }
                }
                $tasks[$key]['issue'] = $rejectReasons;
            }
        }

        return $tasks;
    }

    public function getTasksTime($request)
    {
        $tasks = $this->reportRepository->getTaskSessionTime($request);

        return $tasks;
    }

    public function getDateForTaskBounceReport()
    {
        $firstDay = $this->firstDay();
        $lastDay = $this->getLastDay();

        return $firstDay->format('d/m/Y').' - '.$lastDay->format('d/m/Y');
    }

    public function getBounceReport($request)
    {
        $dateRange = $request->daterange;

        if ($dateRange != '') {
            $daterange = explode(' - ', $dateRange);
            $fromDate = $this->getFromDate($daterange);
            $toDate = $this->getToDate($daterange);

            return $this->reportRepository->getBounceReport($fromDate, $toDate);
        }
    }

    public function getUserRejections($dateRange, $userId)
    {
        if ($dateRange != '') {
            $daterange = explode(' - ', $dateRange);
            $fromDate = $this->getFromDate($daterange);
            $toDate = $this->getToDate($daterange);

            return $this->reportRepository->getUserRejections($fromDate, $toDate, $userId);
        }
    }

    public function getRejectionUsersList($reports, $userId)
    {
        $users = '';
        $checkExistingUsers = $reports->pluck('full_name', 'id')->toArray();
        if ($userId == '') {
            $users = $reports->pluck('full_name', 'id');
        } else {
            if (! array_key_exists($userId, $checkExistingUsers)) {
                $user = User::mailableEmployees()->select('id', 'first_name', 'last_name')->firstWhere('id', $userId);
                $users = [$user->id => $user->full_name];
            }
        }

        return $users;
    }

    public function getFromDate($daterange)
    {
        return Carbon::createFromFormat('d/m/Y', $daterange[0])->format('Y-m-d');
    }

    public function getToDate($daterange)
    {
        return Carbon::createFromFormat('d/m/Y', $daterange[1])->format('Y-m-d');
    }

    public function getLastDay()
    {
        return Carbon::today();
    }

    public function getProjectBillability($request)
    {
        return $this->reportRepository->getProjectBillability($request);
    }

    public function getAllBillableProjects($request)
    {
        return $this->reportRepository->getAllBillableProjects($request);
    }

    /**
     * Retrieves the billable clients based on the given request.
     *
     * @param  mixed  $request  The request object containing the necessary parameters for fetching the billable clients.
     * @return mixed The result of the repository method fetchBillableClients.
     */
    public function getBillableClients($request)
    {
        return $this->reportRepository->fetchBillableClients($request);
    }

    /**
     * Retrieves the billable hours for a specific project within a given date range.
     *
     * @param  mixed  $request  The request data containing the project ID and optional date range.
     * @return \Illuminate\Support\Collection The collection of project billable hours.
     */
    public function getProjectBillableHours($request, $dataDisplayType)
    {
        return $this->reportRepository->fetchProjectBillableHours($request, $dataDisplayType);
    }

    /**
     * Retrieves the data display type based on the given request.
     *
     * @param  \Illuminate\Http\Request  $request  The request object containing the date range.
     * @return string The selected data display type: 'day', 'week', 'month', or 'year'.
     */
    public function getSelectedDataDisplayType($request): string
    {
        if ($request->has('dataDisplayType')) {
            return $request->dataDisplayType;
        }

        $selectedDateRange = $request->dateRange;

        if (empty($selectedDateRange)) {
            return 'day';
        }

        [$startDate, $endDate] = explode(' - ', $selectedDateRange);
        $startDate = trim($startDate);
        $endDate = trim($endDate);

        $startDateCarbon = Carbon::createFromFormat('d/m/Y', $startDate);
        $endDateCarbon = Carbon::createFromFormat('d/m/Y', $endDate);

        $differenceInDays = $startDateCarbon->diffInDays($endDateCarbon);
        $progressGraphViewCutoffs = config('general.progress_graph_view_cutoffs');

        if ($differenceInDays > $progressGraphViewCutoffs['year']) {
            return 'year';
        }

        if ($differenceInDays > $progressGraphViewCutoffs['month']) {
            return 'month';
        }

        if ($differenceInDays > $progressGraphViewCutoffs['week']) {
            return 'week';
        }

        return 'day';
    }
}
