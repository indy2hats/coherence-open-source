<?php

namespace App\Models;

use App\Services\BusinessDays;
use Carbon\Carbon;
use DateTime;
use DB;
use Facades\App\Services\LeaveService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravelista\Comments\Commenter;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;
    use HasPushSubscriptions;
    use Commenter;
    use HasFactory;

    /** The attributes that are mass assignable */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'employee_id',
        'joining_date',
        'phone',
        'address',
        'department_id',
        'designation_id',
        'image_path',
        'role_id',
        'monthly_salary',
        'nick_name',
        'must_change_password',
        'status',
        'contract',
        'wish_notify',
        'dsr_notify',
        'easy_access',
        'gender',
        'leaving_date',
        'email_token',
        'email_token_expired_at',
        'rejoin_date'
    ];

    /** The attributes that should be hidden for arrays.*/
    protected $hidden = [
        'password', 'remember_token',
    ];

    /** The attributes that should be cast to native types */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** Get the user's full name by combining first_name and last_name. */
    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name).' '.$this->last_name;
    }

    /** Set users password encription */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /** Get project_assigned details associated with a user */
    public function users_project()
    {
        return $this->hasMany('App\Models\ProjectAssignedUsers')->has('project');
    }

    /** Get task_assigned details associated with a user */
    public function users_task()
    {
        return $this->hasMany('App\Models\TaskAssignedUsers');
    }

    public function users_task_rejection()
    {
        return $this->hasMany('App\Models\TaskRejection');
    }

    /** Get task_session details associated with a user */
    public function users_task_session()
    {
        return $this->hasMany('App\Models\TaskSession');
    }

    /** Get user with associated with role */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    /** Get designation associated with user */
    public function designation()
    {
        return $this->belongsTo('App\Models\Designation');
    }

    /** Get department associated with user */
    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    /** Get team details associated with a user */
    public function team()
    {
        return $this->hasMany('App\Models\Team');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /** Get the date in day-month-Year. */
    public function getJoiningDateFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->joining_date), 'd/m/Y'));
    }

    public static function returnIdleUsers()
    {
        return DB::select(' SELECT * FROM `users` WHERE status = 1 and role_id not in(select id from roles where name IN("administrator", "client","consultant")) and `id` NOT IN(SELECT `user_id` from `task_sessions` WHERE cast(created_at as date) = CURDATE() and (`current_status` = "started" or `current_status` = "resume" ))');
    }

    /** Get the date in day/month/Year. */
    public function getJoiningDateShowAttribute()
    {
        return ucfirst(date_format(new DateTime($this->joining_date), 'd/m/Y'));
    }

    public function getLeavingDateShowAttribute()
    {
        return ucfirst(($this->leaving_date == '0000-00-00' || $this->leaving_date == null) ? '' : date_format(new DateTime($this->leaving_date), 'd/m/Y'));
    }

    public function leaves()
    {
        return $this->hasMany('App\Models\Leave');
    }

    public function compensatories()
    {
        return $this->hasMany('App\Models\Compensatory');
    }

    public function deviceTokens()
    {
        return $this->hasOne('App\Models\UserDeviceToken');
    }

    public function scopeMailableEmployees($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereNotIn('name', ['administrator', 'client']);
        })->where('status', '1');
    }

    public function scopeGetEmployees($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('name', ['employee', 'project-manager', 'team-lead']);
        })->where('status', '1');
    }

    public function scopeLeaveAdmins($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('name', ['administrator', 'hr-manager', 'project-manager']);
        })->where('status', '1');
    }

    public function scopeProjectAdmins($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('name', ['administrator', 'project-manager']);
        })->where('status', '1');
    }

    public function paidLeaves()
    {
        return $this->hasMany('App\Models\Leave')->where('status', 'Approved')->where('lop', 'No');
    }

    public function compensatory_works()
    {
        return $this->hasMany('App\Models\Compensatory')->where('status', 'Approved');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /** Get the user's full name by combining first_name and last_name. */
    public function getLeaveCountAttribute($fromDate, $toDate)
    {
        $leaveCount = 0;
        $leaves = $this->paidLeaves->where('from_date', '>=', $fromDate)->where('to_date', '<=', $toDate);

        foreach ($leaves as $leave) {
            $leaveCount += LeaveService::getLeaveDaysCount($leave->from_date, $leave->to_date, $leave->session);
        }

        return $leaveCount;
    }

    public function getLeaveCountAttributeByDate($date)
    {
        $leaveCount = 0;
        $leaves = $this->paidLeaves->where('from_date', '<=', $date)->where('to_date', '>=', $date);

        foreach ($leaves as $leave) {
            $leaveCount += LeaveService::getLeaveDaysCount($leave->from_date, $leave->to_date, $leave->session);
        }

        return $leaveCount;
    }

    public function scopeClients($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('name', ['client']);
        })->where('status', '1');
    }

    public function scopeAdmins($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('name', ['administrator']);
        })->where('status', '1');
    }

    public function scopeManagers($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('name', ['project-manager']);
        })->where('status', '1');
    }

    public function scopeHrManagers($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('name', ['hr-manager']);
        })->where('status', '1');
    }

    public function scopeNotClients($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('name', '!=', 'client');
        })->where('status', '1');
    }

    public function scopeWorkers($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('name', ['project-manager', 'employee', 'team-lead']);
        })->where('status', '1');
    }

    public function scopeNotEmployees($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('name', '!=', 'employee');
        })->where('status', '1');
    }

    public function scopeNotAdministrators($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('name', '!=', 'administrator');
        })->where('status', '1');
    }

    public function dailyReports()
    {
        return $this->hasMany('App\Models\DailyStatusReport');
    }

    public function checkIfDsrEntered($date)
    {
        if ($this->hasAnyRole(['administrator', 'client'])) {
            return true;
        }

        $businessDays = new BusinessDays();

        $holidays = Holiday::where('holiday_date', '>=', $date)->where('holiday_date', '<=', $date)->get();

        if ($holidays->count() > 0) {
            $holidayArray = $holidays->pluck('holiday_date')->toArray();
            $businessDays->addHolidays($holidayArray);
        }

        $workingDays = $businessDays->daysBetween(Carbon::parse($date), Carbon::parse($date));
        $workDays = $workingDays - $this->getLeaveCountAttributeByDate($date);

        if ($workDays > 0) {
            $reportAdded = DailyStatusReport::whereAddedOn($date)->whereUserId($this->id)->first();
            if ($reportAdded) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    public function checkAvailableDay($date)
    {
        $weekDays = WeekHoliday::pluck('day')->toArray();
        $dateDay = Carbon::parse($date)->format('l');
        if (in_array($dateDay, $weekDays)) {
            return response()->json(['status' => true, 'message' => 'Weekend', 'data' => 'Weekend', 'color' => '#dddddd']);
        }

        $holidays = Holiday::where('holiday_date', $date)->get();
        foreach ($holidays as $holiday) {
            $dayOfWeek = Carbon::parse($holiday->holiday_date)->format('l');
            if (! in_array($dayOfWeek, $weekDays)) {
                return response()->json(['status' => true, 'message' => 'Holiday', 'data' => $holiday->holiday_name, 'color' => '#faaa69']);
            }
        }

        $leaves = Leave::where('from_date', '<=', $date)
            ->where('to_date', '>=', $date)
            ->where('user_id', $this->id)
            ->get();
        foreach ($leaves as $leave) {
            if ($leave->status == 'Approved') {
                return response()->json(['status' => true, 'message' => 'Leave', 'data' => $leave->session, 'color' => '#dc3545']);
            }
        }

        return response()->json(['status' => false, 'message' => '', 'data' => '', 'color' => '#ffffff']);
    }

    public function getDsrLateDateShowAttribute()
    {
        return ucfirst(date_format(new DateTime($this->dsr_late_date), 'd/m/Y'));
    }

    public function dailyChecklists()
    {
        return $this->belongsToMany('App\DailyChecklist', 'user_daily_checklists', 'user_id', 'checklist_id');
    }

    public function dailyChecklistReports()
    {
        return $this->hasMany('App\Models\UserDailyChecklistUpdate');
    }

    public function scopeIsClient($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('name', ['client']);
        })->where('status', '1');
    }

    public function user_bank_details()
    {
        return $this->hasOne(UserBankDetails::class);
    }

    public function getBankNameAttribute()
    {
        return $this->user_bank_details->bank_name ?? '';
    }

    public function getBranchAttribute()
    {
        return $this->user_bank_details->branch ?? '';
    }

    public function getIfscAttribute()
    {
        return $this->user_bank_details->ifsc ?? '';
    }

    public function getAccountNoAttribute()
    {
        return $this->user_bank_details->account_no ?? '';
    }

    public function getPanAttribute()
    {
        return $this->user_bank_details->pan ?? '';
    }

    public function getUanAttribute()
    {
        return $this->user_bank_details->uan ?? '';
    }

    public function getAllLeaveCountAttribute($fromDate, $toDate)
    {
        $leaveCount = 0;
        $leaves = $this->leaves->where('from_date', '<=', $toDate)->where('to_date', '>=', $fromDate);

        foreach ($leaves as $leave) {
            $leaveCount += LeaveService::getLeaveDaysCount($leave->from_date, $leave->to_date, $leave->session);
        }

        return $leaveCount;
    }

    public function scopeNotConsultant($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('name', '!=', 'consultant');
        });
    }

    public function setGoogle2faCodeAttribute($value)
    {
        $this->attributes['google2fa_secret'] = encrypt($value);
    }

    public function getGoogle2faCodeAttribute($value)
    {
        return empty($value) ? $value : decrypt($value);
    }

    public function scopeLastEmployeeCode($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('name', '!=', 'client');
        })->orderByRaw("CONVERT(SUBSTRING_INDEX(employee_id, 'L', -1), UNSIGNED) DESC")
            ->select('employee_id')
            ->where('employee_id', 'like', '2HL%')
            ->first();
    }

    public function getSessionSlugAttribute()
    {
        if (auth()->user()->designation && array_key_exists(auth()->user()->designation->name, config('session_types.designations'))) {
            return $session_slug = config('session_types.designations.'.auth()->user()->designation->name);
        }

        return $session_slug = config('session_types.roles.'.auth()->user()->role->name);
    }

    public static function returnAllIdleUsers($userlist = null, $request = null)
    {
        $filter = null;
        if ($request) {
            $filter = $request->get('search')['value'];
        }

        /* return DB::select(' SELECT * FROM `users` WHERE status = 1
                            and role_id not in
                            (select id from roles where name IN("administrator", "client","consultant"))
                             and `id` NOT IN(SELECT `user_id` from `task_sessions` WHERE cast(created_at as date) = CURDATE()
                             and (`current_status` = "started" or `current_status` = "resume" ))');*/

        $users = User::with('role', 'leaves', 'users_task_session')
            ->when($userlist != null, function ($query) use ($userlist) {
                return $query->whereIn('id', $userlist);
            })
            ->where('status', '=', 1)
            ->whereHas('role', function ($q) {
                $q->whereNotIn('name', ['administrator', 'client', 'consultant']);
            })
            ->whereDoesntHave('users_task_session', function ($q) {
                $q->where('created_at', 'like', '%'.date('Y-m-d').'%');
                $q->whereIn('current_status', ['started', 'resume']);
            })
            ->whereDoesntHave('leaves', function ($q) {
                $q->where('from_date', '<=', date('Y-m-d'));
                $q->where('to_date', '>=', date('Y-m-d'));
                $q->whereStatus('Approved');
            });

        if ($filter) {
            $users = $users->where(function ($q) use ($filter) {
                $q->where('first_name', 'like', '%'.$filter.'%');
                $q->orWhere('last_name', 'like', '%'.$filter.'%');
            });
        }

        return $users->get();
    }

    public static function returnOnLeaveUsers($users = null, $request = null)
    {
        $filter = null;
        if ($request) {
            $filter = $request->get('search')['value'];
        }

        $leaves = [];
        $holidays = Holiday::pluck('holiday_date')->toArray();
        $weekDays = WeekHoliday::pluck('day')->toArray();
        if (! in_array(date('l', strtotime(date('Y-m-d'))), $weekDays) && ! in_array(date('Y-m-d'), $holidays)) {
            $leaves = Leave::with('users')
                ->when($users != null, function ($query) use ($users) {
                    return $query->whereIn('user_id', $users);
                })
                ->leaveToday();
            if ($filter) {
                $leaves = $leaves->whereHas('users', function ($q) use ($filter) {
                    $q->where('first_name', 'like', '%'.$filter.'%');
                    $q->orWhere('last_name', 'like', '%'.$filter.'%');
                });
            }
            $leaves = $leaves->get();
        }

        return $leaves;
    }

    // Define the TaskAssignedUsersHour relationship
    public function taskAssignedUserHours()
    {
        return $this->hasMany(TaskAssignedUsersHour::class);
    }

    // Define a method to get the sum of hours for a passed date range
    public function getHoursForDateRange($startDate, $endDate)
    {
        return $this->taskAssignedUserHours()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('hour');
    }

    public function getHoursForDate($date)
    {
        return $this->taskAssignedUserHours()
            ->where('date', $date)
            ->sum('hour');
    }

    public function getTasks($date)
    {
        return $this->taskAssignedUserHours()
            ->select('task_id', DB::raw('SUM(hour) as total_hours'))
            ->where('date', $date)
            ->groupBy('task_id')
            ->get();
    }

    public function employeeHikeHistory()
    {
        return $this->hasMany(EmployeeHikeHistory::class);
    }

    public function getAvailableDaysAttribute($workingDays, $startDate, $endDate)
    {
        $availableDays = $workingDays;
        if (Carbon::parse($startDate)->lte(Carbon::parse($this->joining_date))) {
            $businessDays = new BusinessDays();
            $availableDays = $businessDays->daysBetween(Carbon::parse($this->joining_date), Carbon::parse($endDate));
        }

        return $availableDays;
    }
}
