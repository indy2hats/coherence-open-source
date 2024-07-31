<?php

namespace App\Repository;

use App\Models\PayrollUser;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskRejection;
use App\Models\TaskSession;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use DB;

class DashboardRepository
{
    use GeneralTrait;

    public function getTasksForCount()
    {
        return Task::notArchived()->with(['users' => function ($q) {
            $q->where('user_id', '=', $this->getCurrentUserId());
        }])->whereHas('users', function ($query) {
            $query->where('user_id', '=', $this->getCurrentUserId());
        })->has('project')->get();
    }

    public function getTotalHours()
    {
        return DB::select('select sum(billed_today) as billed, sum(total) as total from task_sessions where user_id = ?', [$this->getCurrentUserId()])[0];
    }

    public function getProjects($currentUserId)
    {
        return Project::whereHas('client', function ($q) use ($currentUserId) {
            $q->where('user_id', $currentUserId);
        })->get();
    }

    public function getInProgressTasksIsClient($clientProjects)
    {
        return Task::with(['users'])
                ->where('status', '=', 'In Progress')
                ->whereIn('project_id', $clientProjects)
                ->has('project')
                ->get();
    }

    public function getInProgressTasksNotClient($status)
    {
        return Task::with(['users' => function ($q) {
            $q->where('user_id', '=', $this->getCurrentUserId());
        }])->whereHas('users', function ($query) {
            $query->where('user_id', '=', $this->getCurrentUserId());
        })->where('status', '=', $status)->orderBy('updated_at', 'DESC')->has('project')->get();
    }

    public function getRejectedTasksIsClient($clientProjects)
    {
        return TaskRejection::with('task')->with([
            'task.project' => function ($q) use ($clientProjects) {
                $q->whereIn('id', $clientProjects);
            }
        ])->whereHas('task.project', function ($q) use ($clientProjects) {
            $q->whereIn('id', $clientProjects);
        })->with('rejectedBy')
                    ->where('reason', '!=', '')
                    ->has('task')
                    ->has('task.project')
                    ->get();
    }

    public function getRejectedTasksNotClient()
    {
        return TaskRejection::with('task')
                ->with('task.project')
                ->with('rejectedBy')
                ->where('reason', '!=', '')
                ->where('user_id', $this->getCurrentUserId())
                ->whereRelation('task', 'status', '!=', 'Done')
                ->has('task')
                ->has('task.project')
                ->get();
    }

    public function getTaskSessionsForThisWeek()
    {
        return TaskSession::where('user_id', $this->getCurrentUserId())->where('created_at', '>=', Carbon::now()->startOfWeek()->format('Y-m-d'))->where('created_at', '<=', Carbon::now()->endOfWeek()->format('Y-m-d'))->has('task.project')->get();
    }

    public function getGeneralForChartData($startDate, $endDate)
    {
        return DB::table('expenses')
                ->select(DB::raw("DATE_FORMAT(date, '%Y-%m') AS month"), DB::raw('SUM(amount) AS expense'))
                ->whereBetween('date', [$startDate, $endDate])
                ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m')"))
                ->get()->keyBy('month');
    }

    public function getOverheadsForChartData($startDate, $endDate)
    {
        return DB::table('overheads')
                ->select(DB::raw("DATE_FORMAT(date, '%Y-%m') AS month"), DB::raw('SUM(amount) AS expense'))
                ->whereBetween('date', [$startDate, $endDate])
                ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m')"))
                ->get()->keyBy('month');
    }

    public function getPayrollForChartData($startDate, $endDate)
    {
        return PayrollUser::join('payrolls', 'payrolls.id', '=', 'payroll_users.payroll_id')
                ->select(
                    DB::raw("DATE_FORMAT(payroll_date, '%Y-%m') AS month"),
                    DB::raw('(SUM(payroll_users.monthly_ctc) + SUM(payroll_users.incentives))  AS expense')
                )
                ->whereBetween('payroll_date', [$startDate, $endDate])
                ->groupBy(DB::raw("DATE_FORMAT(payroll_date, '%Y-%m')"))
                ->get()->keyBy('month');
    }
}
