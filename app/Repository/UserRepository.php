<?php

namespace App\Repository;

use App\Models\AssetUser;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use App\Services\LeaveService;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Exception;

class UserRepository
{
    use GeneralTrait;

    protected $model;
    protected $leaveService;

    public function __construct(User $user, LeaveService $leaveService)
    {
        $this->model = $user;
        $this->leaveService = $leaveService;
    }

    public static function getUserId($employee_id)
    {
        $user = User::select('id')->where('employee_id', $employee_id)->first();

        return $user->id;
    }

    public static function getUsersTaskSessionWithDate($date)
    {
        try {
            return User::getEmployees()
                ->whereDate('rejoin_date', '<=', $date)
                ->with(['users_task_session' => function ($query) use ($date) {
                    $query->whereDate('created_at', '=', $date);
                }])
                ->with(['leaves' => function ($query) {
                    $query->where('from_date', '>=', Carbon::now()->subDays(30)->format('Y-m-d'));
                    $query->where('to_date', '<=', Carbon::now()->addDays(30)->format('Y-m-d'));
                }])
                ->orderBy('first_name', 'ASC')
                ->orderBy('last_name', 'ASC')->get();
        } catch (Exception $e) {
            return null;
        }
    }

    public function getFromDate()
    {
        return Carbon::now()->subDays(30)->format('Y-m-d');
    }

    public function getToDate()
    {
        return Carbon::now()->addDays(30)->format('Y-m-d');
    }

    public static function disableTwoFactorAuthentication($userId)
    {
        try {
            return User::where('id', $userId)->update([
                'google2fa_secret' => null
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * get all active employees excluding clients with leaves.
     *
     * @return object
     */
    public function getUsersWithLeaves()
    {
        return $this->model->notClients()->with('leaves');
    }

    /**
     * Gets the total number of leaves taken and total number of Lops of the employees.
     *
     * @param  mixed  $users
     * @param  mixed  $filter
     */
    public function getUsersLeavesAndLopCount($users, $filter)
    {
        foreach ($users as $key => $user) {
            $noOfLeaves = 0;
            $noOfLops = 0;
            foreach ($user->leaves as $userLeave) {
                if ($userLeave->status == 'Approved') {
                    if (isset($filter['year'])) {
                        $userLeave = $this->checkYearAndFormatFromToDate($userLeave, $filter['year']);
                        if (isset($filter['month']) && $filter['month'] != '') {
                            $userLeave = $this->checkMonthAndFormatFromToDate($userLeave, $filter['year'], $filter['month']);
                        }
                    }
                    $leaveCount = $this->leaveService->getLeaveDaysCount($userLeave->from_date, $userLeave->to_date, $userLeave->session);
                    $noOfLeaves += $leaveCount;
                    if ($userLeave->lop == 'Yes') {
                        $noOfLops += $userLeave->session == 'Full Day' ? $leaveCount : 0.5;
                    }
                }
            }
            $users[$key]['totalLeaves'] = $noOfLeaves;
            $users[$key]['totalLops'] = $noOfLops;
            $users[$key]['totalPaidLeaves'] = ($noOfLeaves - $noOfLops);
        }

        return $users;
    }

    /**
     * Checks the filter year and the user leave year and sets date accordingly.
     *
     * @param  mixed  $userLeave
     * @param  mixed  $year
     * @return array
     */
    public function checkYearAndFormatFromToDate($userLeave, $year)
    {
        if (date('Y', strtotime($userLeave->to_date)) != $year) {
            $userLeave->to_date = Carbon::create($year)->endOfYear()->endOfMonth()->format('Y-m-d');
        } elseif (date('Y', strtotime($userLeave->from_date)) != $year) {
            $userLeave->from_date = Carbon::create($year)->startOfMonth()->format('Y-m-d');
        }

        return $userLeave;
    }

    /**
     * Checks the filter year-month against the user leave year-month and sets date accordingly.
     *
     * @param  mixed  $userLeave
     * @param  mixed  $year
     * @param  mixed  $month
     * @return array
     */
    public function checkMonthAndFormatFromToDate($userLeave, $year, $month)
    {
        if (date('m', strtotime($userLeave->to_date)) != $month) {
            $userLeave->to_date = Carbon::create($year, $month)->endOfMonth()->format('Y-m-d');
        } elseif (date('m', strtotime($userLeave->from_date)) != $month) {
            $userLeave->from_date = Carbon::create($year, $month)->startOfMonth()->format('Y-m-d');
        }

        return $userLeave;
    }

    /**
     * get all the approved leaves of the employee based on filter year - month.
     *
     * @param  mixed  $query
     * @param  mixed  $filterValue
     */
    public function filterLeavesByMonthYear($query, $filterValue = null)
    {
        if ($filterValue) {
            $query = $query->with('leaves', function ($subQuery) use ($filterValue) {
                $subQuery->where('status', '=', 'Approved');
                $subQuery->where('from_date', 'like', $filterValue.'%');
                $subQuery->orWhere('to_date', 'like', $filterValue.'%');
            });
        } else {
            $query = $query->with('leaves')->where('status', '=', 'Approved');
        }

        return $query;
    }

    /**
     * get all leaves filtered by field name.
     *
     * @param  mixed  $query
     * @param  mixed  $filterName
     * @param  mixed  $filterValue
     * @return object
     */
    public function filterLeavesByFieldName($query, $filterName, $filterValue)
    {
        return $query->where($filterName, '=', $filterValue);
    }

    public function getAllActiveEmployees()
    {
        return $this->model::active()->notClients()->get();
    }

    public function getUsers()
    {
        return $this->model::with('role', 'department', 'designation')->orderBy('first_name', 'ASC')->orderBy('last_name')->get();
    }

    public function getUser()
    {
        return $this->model::with('users_project', 'role')->orderBy('first_name', 'ASC')->first();
    }

    public function store($request)
    {
        $data = [
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'password' => request('password'),
            'employee_id' => request('employee_id'),
            'joining_date' => $this->getJoiningDate(),
            'phone' => request('phone'),
            'role_id' => request('role_id'),
            'monthly_salary' => request('monthly_salary') ?: 0,
            'nick_name' => request('nick_name'),
            'status' => request('status') == 'on' ? 1 : 0,
            'contract' => request('contract') == 'on' ? 1 : 0,
            'easy_access' => serialize([]),
            'gender' => request('gender'),
            'leaving_date' => null,
            'address' => request('address') ?? null,
            'rejoin_date' => $this->getJoiningDate()
        ];

        if (request('department')) {
            $id = $this->getDepartmentId();
            if ($id) {
                $data += [
                    'department_id' => $id->id,
                ];
            } else {
                $newDepartment = $this->createDepartment(request('department'));
                $data += [
                    'department_id' => $newDepartment->id,
                ];
            }
        }

        if (request('designation')) {
            $id = $this->getDesignationId();
            if ($id) {
                $data += [
                    'designation_id' => $id->id,
                ];
            } else {
                $newDesignation = $this->createDesignation(request('designation'));
                $data += [
                    'designation_id' => $newDesignation->id,
                ];
            }
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('userimage');

            $data += [
                'image_path' => $path,
            ];
        }
        if (request('role_id') == 4) {
            $data['employee_id'] = null;
            $data['phone'] = null;
            $data['monthly_salary'] = 0;
            $data['department_id'] = null;
            $data['designation_id'] = null;
        }

        $user = $this->model::create($data);

        $bankDetails = [
            'bank_name' => request('bank_name'),
            'account_no' => request('account_number'),
            'branch' => request('branch'),
            'ifsc' => request('ifsc'),
            'pan' => request('pan_number'),
            'uan' => request('uan_number'),
        ];

        if (array_filter($bankDetails)) {
            $bankDetails['user_id'] = $user->id;
            $this->createUserBankDetails($bankDetails);
        }

        $name = $this->getRoleName(request('role_id'));
        $user->assignRole($name->name);
    }

    public function update($request, $id)
    {
        $data = [
            'first_name' => request('first_name'),
            'email' => request('email'),
            'last_name' => request('last_name'),
            'user_name' => request('user_name'),
            'employee_id' => request('employee_id'),
            'joining_date' => $this->getJoiningDate(),
            'phone' => request('phone'),
            'role_id' => request('role_id'),
            'monthly_salary' => request('monthly_salary') ?? 0,
            'nick_name' => request('nick_name'),
            'status' => request('status') == 'on' ? 1 : 0,
            'contract' => request('edit_contract') == 'on' ? 1 : 0,
            'gender' => request('gender'),
            'leaving_date' => request('leaving_date') == '' ? null : $this->getLeavingDate(),
            'address' => request('address') ?? null
        ];

        if (request('department')) {
            $department_id = $this->getDepartmentId();
            if ($department_id) {
                $data += [
                    'department_id' => $department_id->id,
                ];
            } else {
                $newDepartment = $this->createDepartment(request('department'));
                $data += [
                    'department_id' => $newDepartment->id,
                ];
            }
        }

        if (request('designation')) {
            $designation_id = $this->getDesignationId();
            if ($designation_id) {
                $data += [
                    'designation_id' => $designation_id->id,
                ];
            } else {
                $newDesignation = $this->createDesignation(request('designation'));
                $data += [
                    'designation_id' => $newDesignation->id,
                ];
            }
        }

        if (request('password')) {
            $data['password'] = request('password');
        }

        if ($request->hasFile('image')) {
            $getimagepath = $this->getUserImagePath($id);
            $path = $request->file('image')->store('userimage');
            $this->deleteStorage($getimagepath->image_path);
            $data += [
                'image_path' => $path,
            ];
        }

        if (request('role_id') == 4) {
            $data['employee_id'] = null;
            $data['phone'] = null;
            $data['monthly_salary'] = 0;
            $data['department_id'] = null;
            $data['designation_id'] = null;
        }

        if (request('leaving_date')) {
            $assets = $this->getAssets($id);
            if (count($assets) > 0) {
                $this->updateAssetUser($id);
                foreach ($assets as $asset) {
                    $this->updateAssetWhere($asset->asset_id, [
                        'status' => 'non_allocated'
                    ]);
                }
            }
        }

        $bankDetails = [
            'bank_name' => request('bank_name'),
            'account_no' => request('account_number'),
            'branch' => request('branch'),
            'ifsc' => request('ifsc'),
            'pan' => request('pan_number'),
            'uan' => request('uan_number'),
        ];

        $userBankDetails = $this->getUserBankDetails($id);

        if ($userBankDetails !== null && (empty(array_filter($bankDetails)) || request('role_id') == 4)) {
            $this->deleteUserBankDetails($id);
        } else {
            $this->updateUserBankDetails($id, $bankDetails);
        }

        $user = $this->getUserByid($id);

        if ($user->status == 0 && $data['status'] == 1) {
            $data['rejoin_date'] = Carbon::now()->format('Y-m-d');
        }

        $name = $this->getRoleName($user->role_id);

        $user->removeRole($name->name);

        $this->model::find($id)->update($data);

        $name = $this->getRoleName(request('role_id'));

        $this->model::find($id)->assignRole($name->name);

        $user = $user->fresh();

        $img = $user->id === $this->getCurrentUserId() ? asset('storage/'.$user->image_path) : '';

        return $img;
    }

    public function updateAssetUser($id)
    {
        AssetUser::where('user_id', $id)->where('status', 'allocated')->update(['status' => 'inactive']);
    }

    public function getDepartmentId()
    {
        return Department::select('id')->where('name', 'like', '%'.request('department').'%')->first();
    }

    public function getDesignationId()
    {
        return Designation::select('id')->where('name', 'like', '%'.request('designation').'%')->first();
    }

    public function getRoleName($roleId)
    {
        return Role::select('name')->where('id', $roleId)->first();
    }

    public function getUserWithDesignationAndDept($id)
    {
        return User::with('designation', 'department')->where('id', $id)->first();
    }

    public function destroy($id)
    {
        $getimagepath = $this->getUserImagePath($id);
        $this->deleteStorage($getimagepath->image_path);

        $name = $this->getRoleName($this->model::find($id)->role_id);
        $this->userRemoveRole($id, $name->name);

        $this->deleteUser($id);
    }

    public function getSingleUser($Id)
    {
        return $this->model::with('users_project.project', 'role')->where('id', $Id)->first();
    }

    public function getUsersForUsersGridAjax($request)
    {
        $query = $this->model::with('role');

        if ($request['filter_value'] != '') {
            if ($request['filter_type'] == 'employee_name') {
                $query = $query->where('first_name', 'like', '%'.$request['filter_value'].'%')
                                        ->orWhere('last_name', 'like', '%'.$request['filter_value'].'%')
                                        ->orWhere('email', 'like', '%'.$request['filter_value'].'%');
            } elseif ($request['filter_type'] == 'role') {
                $query = $query->where('role_id', $request['filter_value']);
            } elseif ($request['filter_type'] == 'employee_type') {
                $queryStr = $request['filter_value'] == 'client' ? '=' : '!=';
                $query = $query->whereRelation('role', 'name', $queryStr, 'client');
            }
        }
        $users = $query->orderBy('first_name', 'ASC')->get();

        $oneUser = $query->first() == null ? $this->model::with('users_project', 'role')->first() : $query->first();

        return [$users, $oneUser];
    }

    public function wishNotified()
    {
        $userId = $this->getCurrentUserId();
        $this->updateUser($userId, ['wish_notify' => 0]);
    }

    public function eodReportNotified()
    {
        $userId = $this->getCurrentUserId();
        $this->updateUser($userId, ['dsr_notify' => 0]);
    }

    public function getUserWithDesignation($info)
    {
        return User::with('designation')->where('id', $info->id)->first();
    }
}
