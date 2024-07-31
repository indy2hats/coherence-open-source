<?php

namespace App\Traits;

use App\Models\Asset;
use App\Models\AssetAttributeValue;
use App\Models\AssetDocument;
use App\Models\AssetTicket;
use App\Models\AssetTicketStatus;
use App\Models\AssetType;
use App\Models\AssetUser;
use App\Models\AssetVendor;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Client;
use App\Models\Compensatory;
use App\Models\DailyStatusReport;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeHikeHistory;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\FixedOverhead;
use App\Models\Guideline;
use App\Models\Holiday;
use App\Models\IssueRecord;
use App\Models\Leave;
use App\Models\Overhead;
use App\Models\OverheadType;
use App\Models\Payroll;
use App\Models\Permission;
use App\Models\Project;
use App\Models\ProjectAssignedUsers;
use App\Models\ProjectCredentials;
use App\Models\QaIssue;
use App\Models\Recruitment;
use App\Models\Role;
use App\Models\SalaryComponent;
use App\Models\Schedule;
use App\Models\SessionType;
use App\Models\Settings;
use App\Models\Task;
use App\Models\TaskAssignedUsers;
use App\Models\TaskAssignedUsersHour;
use App\Models\TaskSession;
use App\Models\TaskStatusType;
use App\Models\TaskTag;
use App\Models\Taxonomy;
use App\Models\TaxonomyList;
use App\Models\Technology;
use App\Models\User;
use App\Models\UserBankDetails;
use App\Models\UserCredential;
use App\Models\UserWish;
use App\Models\WeekHoliday;
use App\Models\WorkNote;
use App\Services\BusinessDays;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

trait GeneralTrait
{
    public function isClient()
    {
        return Auth::user()->hasRole('client');
    }

    public function isEmployee()
    {
        return Auth::user()->hasrole('employee');
    }

    public function getClients()
    {
        return Client::select('id', 'company_name')->orderBy('company_name', 'ASC')->get();
    }

    public function getClientsListByUserId($isClient, $currentUserId)
    {
        $clientsQuery = Client::select('id', 'company_name')->orderBy('company_name', 'ASC');

        if ($isClient) {
            $clientsQuery->where('user_id', $currentUserId);
        }

        return $clientsQuery->get();
    }

    public function getCurrentUser()
    {
        return Auth::user();
    }

    public function getCurrentUserId()
    {
        return auth()->user()->id;
    }

    public function getTechnologies()
    {
        return Technology::where('status', 'active')->get();
    }

    public function getUserWorkers()
    {
        return User::Workers()->select('id', 'first_name', 'last_name')->orderBy('first_name', 'ASC')->get();
    }

    public function getTaskAssignedUserHours($startDate, $endDate)
    {
        return TaskAssignedUsersHour::whereBetween('date', [$startDate, $endDate])->sum('hour');
    }

    public function getAdmins()
    {
        return User::admins()->orderBy('first_name', 'ASC')->get();
    }

    public function getTags()
    {
        return TaskTag::select('title', 'slug')->orderBy('title')->get();
    }

    public function getParentTasks($id)
    {
        return Task::where([
            'project_id' => $id,
            'parent_id' => null,
        ])->notArchived()->orderBy('title', 'Asc')->get();
    }

    public function getProjectManagersData($id)
    {
        return ProjectAssignedUsers::with('user', 'user.designation')->where('project_id', $id)->get();
    }

    public function getTask($task_id)
    {
        return Task::where('id', $task_id)->first();
    }

    public function getUser($user_id)
    {
        return User::where('id', $user_id)->first();
    }

    public function getUsers()
    {
        return User::orderBy('first_name', 'ASC')->get();
    }

    public function getProject($id)
    {
        return Project::where('id', $id)->first();
    }

    public function findProject($id)
    {
        return Project::find($id);
    }

    public function getProjectAssignedUsers()
    {
        return ProjectAssignedUsers::where('project_id', request('project_id'))->get();
    }

    public function getTaskAssignedUsers($task_id)
    {
        return TaskAssignedUsers::where('task_id', $task_id)->get();
    }

    public function getUserNotClients()
    {
        return User::notClients()->orderBy('first_name', 'ASC')->get();
    }

    public function getTaskStatusTypes()
    {
        return TaskStatusType::orderBy('order', 'ASC')->get();
    }

    public function getQaIssues()
    {
        return QaIssue::orderBy('title', 'ASC')->get();
    }

    public function findTask($id)
    {
        return Task::find($id);
    }

    public function getActiveUsers()
    {
        return User::active()->orderBy('first_name', 'ASC')->get();
    }

    public function getActiveUsersOrdeByFirstName()
    {
        return User::active()->orderBy('first_name')->get();
    }

    public function getNotClientsActiveUsers()
    {
        return User::notClients()->active()->get();
    }

    public function getTaxonomyId()
    {
        return Taxonomy::select('id')->where('slug', 'checklist')->first()->id;
    }

    public function deleteTaxonomyList($id)
    {
        TaxonomyList::find($id)->delete();
    }

    public function getWorkNotes($userId)
    {
        return WorkNote::where('user_id', $userId)->orderBy('id', 'DESC')->get();
    }

    public function getProjectsOrderByProjectName()
    {
        return Project::select('id', 'project_name')->orderBy('project_name')->get();
    }

    public function findIssue($id)
    {
        return IssueRecord::find($id);
    }

    public function deleteIssue($id)
    {
        return IssueRecord::find($id)->delete();
    }

    public function getProjectCredentialsByProjectId($id)
    {
        return ProjectCredentials::with('project')->where('project_id', $id)->get();
    }

    public function getProjectCredentials($id)
    {
        return ProjectCredentials::where('id', $id)->first();
    }

    public function deleteProjectCredentials($id)
    {
        ProjectCredentials::find($id)->delete();
    }

    public function deleteProjectAssignedUsers($project_id)
    {
        ProjectAssignedUsers::where('project_id', $project_id)->delete();
    }

    public function getUserCredentials($id)
    {
        return UserCredential::whereUserId($id)->get();
    }

    public function deleteUserCredentials($id)
    {
        UserCredential::find($id)->delete();
    }

    public function getGuidelines()
    {
        return Guideline::select('type', 'id', 'title')->get();
    }

    public function findGuideLine($id)
    {
        return Guideline::find($id);
    }

    public function deleteGuideline($id)
    {
        Guideline::find($id)->delete();
    }

    public function getGuideline()
    {
        return Guideline::where('id', request('id'))->first();
    }

    public function getSessionTypes()
    {
        return SessionType::pluck('title', 'slug')->toArray();
    }

    public function getSessionTypesSlug()
    {
        return SessionType::pluck('slug')->toArray();
    }

    public function getPreviousMonth()
    {
        return Carbon::now()->subMonth()->format('Y-m-d');
    }

    public function getNextMonth()
    {
        return Carbon::now()->addMonth()->format('Y-m-d');
    }

    public function getCurrentMonth()
    {
        return date('Y-m-d');
    }

    public function getlastWorkingDate()
    {
        $businessDays = new BusinessDays();

        return $businessDays->getLastWorkingDay(Carbon::yesterday())->format('d/m/Y');
    }

    public function getTaskWithChildren($id)
    {
        return Task::with('children')->where('id', $id)->first();
    }

    public function getHoliday($date)
    {
        return Holiday::where('holiday_date', 'like', '%'.$date.'%')->first();
    }

    public function getWeekHolidays()
    {
        return WeekHoliday::all();
    }

    public function updateTask($id, $data)
    {
        Task::find($id)->update($data);
    }

    public function findTaskSession($id)
    {
        return TaskSession::find($id);
    }

    public function updateTaskSession($id, $data)
    {
        TaskSession::find($id)->update($data);
    }

    public function previousWeekStart()
    {
        return Carbon::now()->subWeek()->startOfWeek(Carbon::SUNDAY)->format('M d, Y');
    }

    public function previousWeekEnd()
    {
        return Carbon::now()->subWeek()->endOfWeek(Carbon::SATURDAY)->format('M d, Y');
    }

    public function firstDay()
    {
        return Carbon::today()->startOfMonth();
    }

    public function lastDay()
    {
        return Carbon::yesterday();
    }

    public function getFirstDayLastDay($firstDay, $lastDay)
    {
        return $firstDay->format('M d, Y').' - '.$lastDay->format('M d, Y');
    }

    public function getYear()
    {
        return date('Y');
    }

    public function getMonth()
    {
        return date('F Y');
    }

    public function convertImagetoBase64($url)
    {
        $logoPath = asset($url);
        try {
            $imageData = file_get_contents(public_path($url));
        } catch (\Exception $e) {
            Log::error('Error getting image data: '.$e->getMessage());

            return '';
        }

        $type = pathinfo($logoPath, PATHINFO_EXTENSION);

        return   'data:image/'.$type.';base64,'.base64_encode($imageData);
    }

    public function getSalaryComponentById($id)
    {
        return SalaryComponent::find($id);
    }

    public function getEmployees()
    {
        return User::getEmployees()->get();
    }

    public function getSalaryCurrency()
    {
        return Settings::where('slug', 'salary_currency')->value('value');
    }

    public function getEmployeeHikeHistoryById($id)
    {
        return EmployeeHikeHistory::find($id);
    }

    public function getRoles()
    {
        return Role::orderBy('name', 'ASC')->get();
    }

    public function getJoiningDate()
    {
        return Carbon::createFromFormat('d/m/Y', request('joining_date'))->format('Y-m-d');
    }

    public function getLeavingDate()
    {
        return Carbon::createFromFormat('d/m/Y', request('leaving_date'))->format('Y-m-d');
    }

    public function createDepartment($name)
    {
        return Department::create(['name' => $name]);
    }

    public function createDesignation($name)
    {
        return Designation::create(['name' => $name]);
    }

    public function getUserByid($id)
    {
        return User::find($id);
    }

    public function getUserImagePath($id)
    {
        return User::select('image_path')->where('id', $id)->first();
    }

    public function getClientImagePath($id)
    {
        return Client::select('image')->where('id', $id)->first();
    }

    public function getUserBankDetails($id)
    {
        return UserBankDetails::where('user_id', $id)->first();
    }

    public function deleteUserBankDetails($id)
    {
        UserBankDetails::where('user_id', $id)->delete();
    }

    public function updateUserBankDetails($id, $bankDetails)
    {
        UserBankDetails::updateOrCreate(['user_id' => $id], $bankDetails);
    }

    public function userRemoveRole($userId, $roleName)
    {
        User::find($userId)->removeRole($roleName);
    }

    public function deleteUser($id)
    {
        User::find($id)->delete();
    }

    public function getAllRoles()
    {
        return Role::all();
    }

    public function getAllUsers()
    {
        return User::all();
    }

    public function getDesignationNames()
    {
        return Designation::select('name')->get();
    }

    public function getDepartmentNames()
    {
        return Department::select('name')->get();
    }

    public function updateUser($id, $data)
    {
        User::find($id)->update($data);
    }

    public function createUserBankDetails($bankDetails)
    {
        UserBankDetails::create($bankDetails);
    }

    public function getClientById($id)
    {
        return Client::where('id', $id)->first();
    }

    public function getHolidayById($id)
    {
        return Holiday::where('id', $id)->first();
    }

    public function deleteHoliday($id)
    {
        Holiday::find($id)->delete();
    }

    public function getUserWishList()
    {
        return UserWish::get();
    }

    public function getRecruitmentNames()
    {
        return Recruitment::orderBy('name', 'ASC')->get();
    }

    public function createRecruitment($data)
    {
        return Recruitment::create($data);
    }

    public function createSchedule($data)
    {
        Schedule::create($data);
    }

    public function getRecruitmentById($id)
    {
        return Recruitment::find($id);
    }

    public function getLeaves()
    {
        return config('leaves');
    }

    public function deleteLeave($id)
    {
        Leave::find($id)->delete();
    }

    public function findCompensatoryById($id)
    {
        return Compensatory::find($id);
    }

    public function deleteCompensatory($id)
    {
        Compensatory::find($id)->delete();
    }

    public function findLeaveById($id)
    {
        return Leave::find($id);
    }

    public function updateLeave($id, $data)
    {
        Leave::find($id)->update($data);
    }

    public function updateSettings($slug, $data)
    {
        Settings::where('slug', $slug)->update(['value' => $data]);
    }

    public function createTechnology($data)
    {
        Technology::create($data);
    }

    public function getTechnologyById($id)
    {
        return Technology::where('id', $id)->first();
    }

    public function updateTechnology($id, $data)
    {
        Technology::find($id)->update($data);
    }

    public function deleteTechnology($id)
    {
        Technology::find($id)->delete();
    }

    public function getOverHeadType()
    {
        return OverheadType::where('name', request('type'))->get();
    }

    public function createOverHeadType($data)
    {
        OverheadType::create($data);
    }

    public function createOverHead($data)
    {
        Overhead::create($data);
    }

    public function getOverHeadById($id)
    {
        return Overhead::where('id', $id)->first();
    }

    public function updateOverHead($id, $data)
    {
        Overhead::find($id)->update($data);
    }

    public function deleteOverHead($id)
    {
        Overhead::find($id)->delete();
    }

    public function getFixedOverHeads()
    {
        return FixedOverhead::orderBy('type', 'ASC')->get();
    }

    public function deleteFixedOverHead($id)
    {
        FixedOverhead::find($id)->delete();
    }

    public function updateFixedOverHead($id, $data)
    {
        FixedOverhead::find($id)->update($data);
    }

    public function getFixedOverHeadById($id)
    {
        return FixedOverhead::where('id', $id)->first();
    }

    public function createFixedOverHead($data)
    {
        FixedOverhead::create($data);
    }

    public function getOverHeadTypes()
    {
        return OverheadType::all();
    }

    public function createExpenseType($data)
    {
        ExpenseType::create($data);
    }

    public function createExpense($data)
    {
        Expense::create($data);
    }

    public function getExpenseById($id)
    {
        return Expense::where('id', $id)->first();
    }

    public function updateExpense($id, $data)
    {
        Expense::find($id)->update($data);
    }

    public function deleteExpense($id)
    {
        Expense::find($id)->delete();
    }

    public function getAllExpenseTypes()
    {
        return ExpenseType::all();
    }

    public function roles()
    {
        return Role::get();
    }

    public function permissions()
    {
        return Permission::get();
    }

    public function roleNotAdministrator()
    {
        return Role::where('name', '!=', 'administrator')->get();
    }

    public function createRole($data)
    {
        Role::create($data);
    }

    public function createPermission($data)
    {
        return Permission::create($data);
    }

    public function getAdministratorRole()
    {
        return Role::whereName('administrator')->first();
    }

    public function findRoleById($id)
    {
        return Role::find($id);
    }

    public function sessionTypes()
    {
        return SessionType::get();
    }

    public function createSessionType($data)
    {
        return SessionType::create($data);
    }

    public function deleteSessionType($id)
    {
        SessionType::find($id)->delete();
    }

    public function deleteSettings($slug)
    {
        Settings::where('slug', $slug)->delete();
    }

    public function getSessionTypeById($id)
    {
        return SessionType::find($id);
    }

    public function updateSessionType($id, $data)
    {
        SessionType::find($id)->update($data);
    }

    public function getDepartments()
    {
        return Department::all();
    }

    public function createUser($data)
    {
        return User::create($data);
    }

    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function checkPassword($value, $hashedValue)
    {
        return Hash::check($value, $hashedValue);
    }

    public function sendNotification($notifiables, $notification)
    {
        Notification::send($notifiables, $notification);
    }

    public function putSession($key, $value)
    {
        Session::put($key, $value);
    }

    public function getSession($key)
    {
        return Session::get($key);
    }

    public function getUserByEmailToken($token)
    {
        return User::where('email_token', $token)->first();
    }

    public function createTaxonomyList($data)
    {
        return TaxonomyList::create($data);
    }

    public function updateGuideline($id, $data)
    {
        return Guideline::find($id)->update($data);
    }

    public function createGuideLine($data)
    {
        Guideline::create($data);
    }

    public function deleteSalaryComponent($id)
    {
        SalaryComponent::find($id)->delete();
    }

    public function updateSalaryComponent($id, $data)
    {
        SalaryComponent::find($id)->update($data);
    }

    public function createSalaryComponent($data)
    {
        SalaryComponent::create($data);
    }

    public function getAllQaIssues()
    {
        return QaIssue::all();
    }

    public function createDailyStatusReport($input)
    {
        DailyStatusReport::create($input);
    }

    public function getHolidays()
    {
        return Holiday::all();
    }

    public function findPayrollById($id)
    {
        return Payroll::find($id);
    }

    public function createWeekHoliday($data)
    {
        WeekHoliday::create($data);
    }

    public function createLeave($data)
    {
        return Leave::create($data);
    }

    public function getAllFixedOverHeads()
    {
        return FixedOverhead::all();
    }

    public static function updatePayroll($id, $data)
    {
        return Payroll::find($id)->update($data);
    }

    public function getSessionTypeTitle($sessionType)
    {
        return SessionType::where('slug', $sessionType)->value('title');
    }

    public function createAsset($data)
    {
        return Asset::create($data);
    }

    public function createAssetDocument($data)
    {
        AssetDocument::create($data);
    }

    public function updateAsset($id, $data)
    {
        Asset::find($id)->update($data);
    }

    public function updateAssetWhere($id, $data)
    {
        Asset::where('id', $id)->update($data);
    }

    public function findAssetById($id)
    {
        return Asset::find($id);
    }

    public function deleteAsset($id)
    {
        Asset::find($id)->delete();
    }

    public function createAssetUser($data)
    {
        AssetUser::create($data);
    }

    public function updateAssetUser($id, $data)
    {
        AssetUser::find($id)->update($data);
    }

    public function findAssetUserById($id)
    {
        return AssetUser::find($id);
    }

    public function createAssetTicket($data)
    {
        AssetTicket::create($data);
    }

    public function updateAssetTicket($id, $data)
    {
        AssetTicket::find($id)->update($data);
    }

    public function deleteAssetDocument($id)
    {
        AssetDocument::find($id)->delete();
    }

    public function createAssetTicketStatus($data)
    {
        AssetTicketStatus::create($data);
    }

    public function updateAssetTicketStatus($id, $data)
    {
        AssetTicketStatus::find($id)->update($data);
    }

    public function deleteAssetTicketStatus($id)
    {
        AssetTicketStatus::find($id)->delete();
    }

    public function createAssetVendor($data)
    {
        AssetVendor::create($data);
    }

    public function updateAssetVendor($id, $data)
    {
        AssetVendor::find($id)->update($data);
    }

    public function deleteAssetVendor($id)
    {
        AssetVendor::find($id)->delete();
    }

    public function createAssetType($data)
    {
        return AssetType::create($data);
    }

    public function updateAssetType($id, $data)
    {
        return AssetType::find($id)->update($data);
    }

    public function deleteAssestType($id)
    {
        AssetType::find($id)->delete();
    }

    public function createAttribute($data)
    {
        return Attribute::create($data)->id;
    }

    public function updateAttribute($id, $data)
    {
        return Attribute::find($id)->update($data);
    }

    public function createAttributeValues($data)
    {
        return AttributeValue::create($data);
    }

    public function deleteAttributeValuesOfAttribute($id)
    {
        return AttributeValue::where('attribute_id', $id)->delete();
    }

    public function deleteAssetAttributeValues($id)
    {
        AssetAttributeValue::where('asset_id', $id)->delete();
    }

    public function deleteStorage($path)
    {
        Storage::delete($path);
    }

    public function getAssets($id)
    {
        return AssetUser::where('user_id', $id)->where('status', 'allocated')->get();
    }

    public function createAssetAttributeValues($data)
    {
        return  AssetAttributeValue::create($data);
    }

    public function updateProject($id, $data)
    {
        Project::find($id)->update($data);
    }
}
