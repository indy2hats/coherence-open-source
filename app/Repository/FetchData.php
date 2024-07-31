<?php

namespace App\Repository;

use App\Models\Task;
use App\Models\TaskSession;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class FetchData
{
    /** Function which return all users */
    public function getAllusers()
    {
        return  User::where('status', 1)->get();
    }

    /** Function to return task details of a user in one week */
    public function getTaskDetails($id, $year, $week)
    {
        return TaskSession::with('task', 'task.project', 'user')
            ->where('user_id', $id)
            ->where('created_at', '>=', date('Y-m-d', strtotime($year.'W'.$week.'1')))
            ->where('created_at', '<=', date('Y-m-d', strtotime($year.'W'.$week.'7')))
            ->orderBy('task_id')->get();
    }

    /** Function to return users who has not entered timesheet for a date range  */
    public function getOneWeekEmptyTimesheetUsers($startDate, $endDate)
    {
        return User::getEmployees()
            ->whereNotIn(
                'id',
                DB::table('task_sessions')
                    ->where('created_at', '>=', $startDate)
                    ->where('created_at', '<=', $endDate)
                    ->pluck('user_id')
            )->get();
    }

    /**  Function to return users who has not entered timesheet for 2 days*/
    public function getTwoDaysEmptyTimesheetUsers($yesterday, $dayBeforeYesterday)
    {
        return User::mailableEmployees()->whereNotIn('id', function ($query) use ($yesterday, $dayBeforeYesterday) {
            $query->select('user_id')->from('task_sessions')->where('created_at', 'like', '%'.$yesterday.'%')->orWhere('created_at', 'like', '%'.$dayBeforeYesterday.'%');
        })->where('status', 1)->get();
    }

    /** Function to return overdue tasks */
    public function getOverdueTasks()
    {
        return Task::with('users', 'project')->where(function ($query) {
            $query->where('end_date', '<', date('Y-m-d'))->orWhereRaw('cast(estimated_time AS DECIMAL(10,2)) < cast(time_spent AS DECIMAL(10,2))');
        })->orderBy('title', 'ASC')->whereIn('status', config('overdue-status'))->take(10)->get();
    }

    /** Function to  create a view named mail_week */
    public function toCreateViewMailWeeek($startDate, $endDate)
    {
        return DB::select("create or replace view mail_week as (select user_id,sum(total) as total
                          from task_sessions
                          inner join users
                          on task_sessions.user_id = users.id
                          inner join roles
                          on users.role_id = roles.id
                          where task_sessions.created_at>='".$startDate."' and task_sessions.created_at<='".$endDate."'
                          and roles.name != 'administrator'
                          and users.status =1
                          group by task_sessions.user_id)");
    }

    /** Function to return employees with timesheet entry < 35 Hours their total hours spent */
    public function getEmployeesBelowThirtyFiveHours()
    {
        return DB::select('select mail_week.total,users.first_name, users.id, users.last_name,users.email from users,mail_week where mail_week.total<2100 and users.id=mail_week.user_id ');
    }

    /** Function to return employees with zero hours */
    public function getEmployeesWithZeroHours()
    {
        $mail_week_users = DB::select('select user_id from mail_week');

        return User::select('id', 'first_name', 'last_name', 'email')->mailableEmployees()->whereNotIn(
            'id',
            Arr::pluck($mail_week_users, 'user_id')
        )->get()->toArray();
    }

    /** Function to return employees who are Idle */
    public function getAllIdleUsers()
    {
        return User::whereNotIn('id', function ($query) {
            $query->select('user_id')->from('task_sessions')->where('created_at', 'like', '%'.date('Y-m-d').'%')->where('current_status', 'started');
        })->get();
    }

    /** Function to return session users who are Idle for 30 min*/
    public function getSessionsNotIdle($id, $formatted_date)
    {
        return TaskSession::where('user_id', $id)->where('updated_at', '>=', $formatted_date)->get();
    }

    /** Function to check last days session timer and send notification*/
    public function checkLastDaySession()
    {
        $task = TaskSession::with('task')->where('current_status', 'started')->where('user_id', Auth::user()->id)->where('created_at', '<', date('Y-m-d'))->first();
        if ($task) {
            Auth::user()->notify(new \App\Notifications\NotifySession('title', 'body'));
        }
    }

    public static function getActiveEmployees()
    {
        return User::getEmployees()->get();
    }

    public static function getUserWorkDaysCountWithDate($user, $dateRange)
    {
        $workDays = 0;
        foreach ($dateRange as $checkDate) {
            $workDays += self::userDaysOnWork(Carbon::parse($checkDate), $user->leaves);
        }

        return $workDays;
    }

    public static function userDaysOnWork($checkDate, $userLeaves)
    {
        if ($userLeaves->isEmpty()) {
            return 1;
        }
        $userLeavesRange = $userLeaves->pluck('from_date', 'to_date')->toArray();
        foreach ($userLeavesRange as $fromDate => $toDate) {
            if ($checkDate->between($fromDate, $toDate)) {
                return 0;
            }
        }
        $userLeavesType = $userLeaves->where('from_date', '<=', $checkDate->format('Y-m-d'))
            ->where('to_date', '>=', $checkDate->format('Y-m-d'))->pluck('session')->toArray();

        if (empty($userLeavesType)) {
            return 1;
        }
        if (in_array('Full Day', $userLeavesType)) {
            return 0;
        }
        if (in_array('First Half', $userLeavesType) || in_array('Second Half', $userLeavesType)) {
            return 0.5;
        }

        return 1;
    }

    public static function getTaskSessionWithDateRange($startDate, $endDate)
    {
        return User::getEmployees()->with(['users_task_session' => function ($query) use ($startDate, $endDate) {
            $query->whereDate('created_at', '>=', $startDate);
            $query->whereDate('created_at', '<=', $endDate);
        }])->orderBy('first_name', 'ASC')
            ->orderBy('last_name', 'ASC')->get();
    }
}
