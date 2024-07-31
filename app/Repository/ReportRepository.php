<?php

namespace App\Repository;

use App\Models\Client;
use App\Models\Holiday;
use App\Models\Project;
use App\Models\TaskRejection;
use App\Models\TaskSession;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportRepository
{
    use GeneralTrait;

    public static function getProjects($fromDate, $toDate)
    {
        return DB::select("select sum(task_sessions.total) as total,sum(task_sessions.billed_today) as billed_today,projects.project_name,projects.project_id,projects.id,projects.id,clients.company_name from projects,tasks,task_sessions,clients where clients.id=projects.client_id and projects.id=tasks.project_id and tasks.id=task_sessions.task_id and projects.created_at >= '".$fromDate."' and projects.created_at <= '".$toDate."' and projects.deleted_at is null GROUP by projects.project_id");
    }

    public function getTasks($id)
    {
        return DB::select('select sum(task_sessions.total) as total,sum(task_sessions.billed_today) as billed_today,tasks.title from tasks,task_sessions where tasks.project_id= ? and tasks.id=task_sessions.task_id GROUP by task_sessions.task_id', [$id]);
    }

    public function getUsers($fromDate, $toDate)
    {
        return DB::select("select sum(task_sessions.total) as total,sum(task_sessions.billed_today) as billed_today,users.first_name,users.last_name from task_sessions,users where users.id=task_sessions.user_id and users.created_at >= '".$fromDate."' and users.created_at <= '".$toDate."' GROUP by task_sessions.user_id");
    }

    public function getClients($fromDate, $toDate)
    {
        return DB::select("select sum(task_sessions.total) as total,sum(task_sessions.billed_today) as billed_today,clients.company_name from projects,tasks,task_sessions,clients where clients.id=projects.client_id and clients.created_at >= '".$fromDate."' and clients.created_at <= '".$toDate."'  and projects.id=tasks.project_id and tasks.id=task_sessions.task_id GROUP by clients.id");
    }

    public function getHolidays($fromDate, $toDate)
    {
        return Holiday::where('holiday_date', '>=', $fromDate)->where('holiday_date', '<=', $toDate)->get();
    }

    public function getUsersForEmployeePerformanceSearch($fromDate, $toDate)
    {
        return User::mailableEmployees()->with(['users_task_session' => function ($query) use ($fromDate, $toDate) {
            $query->whereDate('created_at', '>=', $fromDate);
            $query->whereDate('created_at', '<=', $toDate);
        },
            'users_task_rejection' => function ($query) use ($fromDate, $toDate) {
                $query->where('reason', '!=', '');
                $query->whereDate('updated_at', '>=', $fromDate);
                $query->whereDate('updated_at', '<=', $toDate);
            },
            'users_task' => function ($query) use ($fromDate, $toDate) {
                $query->whereDate('updated_at', '>=', $fromDate);
                $query->whereDate('updated_at', '<=', $toDate);
            }])->with('paidLeaves')->orderBy('first_name', 'ASC')
        ->orderBy('last_name', 'ASC')->get();
    }

    public function getDailyReports($date)
    {
        return User::mailableEmployees()->orderBy('first_name')->with(['dailyReports' => function ($query) use ($date) {
            $query->where('added_on', $date);
        }])->get();
    }

    public function getTasksForTaskBounceReport($tasks, $userId, $projectId, $severity)
    {
        if ($userId != '') {
            $tasks = $tasks->where('user_id', $userId);
        }

        if ($projectId != '') {
            $tasks = $tasks->whereHas('task', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            });
        }

        if ($severity != '') {
            $tasks = $tasks->where('severity', $severity);
        }

        return $tasks->get();
    }

    public function getTaskSessionTime($request)
    {
        if ((! isset($request->taskId) && ! isset($request->userId)) ||
           ((isset($request->taskId) && $request->taskId == '') && (isset($request->userId) && $request->userId == ''))) {
            return [];
        }
        $taskSessions = TaskSession::with('task', 'user')
                    ->selectRaw('task_sessions.*, SUM(total) AS total_hours')
                    ->join('tasks', 'task_sessions.task_id', '=', 'tasks.id')
                    ->join('users', 'task_sessions.user_id', '=', 'users.id');

        if (isset($request->taskId) && $request->taskId != '') {
            $taskSessions->where('task_sessions.task_id', $request->taskId)
                            ->groupBy('task_sessions.user_id')
                            ->orderBy('user_id');
        }

        if (isset($request->userId) && $request->userId != '') {
            $taskSessions->where('task_sessions.user_id', $request->userId)
                         ->groupBy('task_sessions.task_id')
                         ->orderBy('task_id');
        }

        if (isset($request->daterange) && $request->daterange != '') {
            $fromDate = null;
            $toDate = null;
            $daterange = explode(' - ', $request->daterange);
            $toDate = Carbon::parse($daterange[1])->endOfDay()->toDateTimeString();

            if ($daterange[0] == 'Invalid date') {
                $taskSessions->where('task_sessions.created_at', '<=', "$toDate");
            } else {
                $fromDate = Carbon::parse($daterange[0])->startOfDay()->toDateTimeString();
                $taskSessions->whereBetween('task_sessions.created_at', ["$fromDate", "$toDate"]);
            }
            $taskSessions->groupBy('task_sessions.user_id')
                         ->orderBy('user_id');
        }

        if (isset($request->greaterThan) && $request->greaterThan != '') {
            $taskSessions->havingRaw('SUM(total) >= ?', [$request->greaterThan * 60]);
        }

        if (isset($request->lessThan) && $request->lessThan != '') {
            $taskSessions->havingRaw('SUM(total) <= ?', [$request->lessThan * 60]);
        }

        $taskSessions = $taskSessions->get();

        return $taskSessions;
    }

    public function getBounceReport($fromDate, $toDate)
    {
        return User::mailableEmployees()->with(['users_task' => function ($query) {
            $query->whereHas('task', function ($q) {
                $q->whereRelation('project', 'deleted_at', '=', null);
                $q->where('status', 'Done');
            });
        }])->withCount(['users_task_rejection as total_rejections' => function ($query) use ($fromDate, $toDate) {
            $query->whereHas('task', function ($q) {
                $q->whereRelation('project', 'deleted_at', '=', null);
            });
            $query->where('reason', '!=', '');
            $query->whereDate('updated_at', '>=', $fromDate);
            $query->whereDate('updated_at', '<=', $toDate);
        }, 'users_task_rejection as critical_rejections_count' => function ($query) use ($fromDate, $toDate) {
            $query->whereHas('task', function ($q) {
                $q->whereRelation('project', 'deleted_at', '=', null);
            });
            $query->where('reason', '!=', '');
            $query->whereDate('updated_at', '>=', $fromDate);
            $query->whereDate('updated_at', '<=', $toDate);
            $query->where('severity', 'Critical');
        }, 'users_task_rejection as high_rejections_count' => function ($query) use ($fromDate, $toDate) {
            $query->whereHas('task', function ($q) {
                $q->whereRelation('project', 'deleted_at', '=', null);
            });
            $query->where('reason', '!=', '');
            $query->whereDate('updated_at', '>=', $fromDate);
            $query->whereDate('updated_at', '<=', $toDate);
            $query->where('severity', 'High');
        }])
            ->orderBy('first_name', 'ASC')
            ->orderBy('last_name', 'ASC')->get();
    }

    public function getUserRejections($fromDate, $toDate, $userId)
    {
        $query = User::mailableEmployees()->with(['users_task' => function ($query) {
            $query->whereHas('task', function ($q) {
                $q->whereRelation('project', 'deleted_at', '=', null);
                $q->where('status', 'Done');
            });
        }])->withCount(['users_task_rejection as total_rejections' => function ($query) use ($fromDate, $toDate) {
            $query->whereHas('task', function ($q) {
                $q->whereRelation('project', 'deleted_at', '=', null);
            });
            $query->where('reason', '!=', '');
            $query->whereDate('updated_at', '>=', $fromDate);
            $query->whereDate('updated_at', '<=', $toDate);
        }])
            ->having('total_rejections', '>', 0)
            ->orderBy('first_name', 'ASC')
            ->orderBy('last_name', 'ASC');

        if (isset($userId) && $userId != '') {
            $query->where('id', $userId);
        }

        return $query->get();
    }

    public function getTaskRejections()
    {
        return TaskRejection::with('task')->with('users')->with('rejectedBy')->where('reason', '!=', '')->has('task')->has('task.project');
    }

    public function getTaskSession($date)
    {
        return TaskSession::with('task', 'user', 'task.project')
                    ->where('user_id', $this->getCurrentUserId())
                    ->whereDate('created_at', '=', $date)
                    ->get()->sortBy('task.project_id');
    }

    public static function getProjectBillability($request)
    {
        $projectQuery = Project::select(
            DB::raw('SUM(task_sessions.total) as time_spent'),
            DB::raw('SUM(task_sessions.billed_today) as billed_time'),
            'projects.project_name',
            'projects.project_id',
            'projects.id',
            'projects.id',
            'clients.company_name'
        )
        ->join('tasks', 'projects.id', '=', 'tasks.project_id')
        ->join('task_sessions', 'tasks.id', '=', 'task_sessions.task_id')
        ->join('clients', 'clients.id', '=', 'projects.client_id')
        ->whereNull('projects.deleted_at');

        if (isset($request->daterange) && $request->daterange != '') {
            $daterange = explode(' - ', $request->daterange);
            $fromDate = Carbon::createFromFormat('d/m/Y', $daterange[0])->format('Y-m-d');
            $toDate = Carbon::createFromFormat('d/m/Y', $daterange[1])->format('Y-m-d');
        } else {
            $fromDate = Carbon::now()->subMonth()->startOfDay()->format('Y-m-d');
            $toDate = Carbon::now()->endOfDay()->format('Y-m-d');
        }
        $projectQuery->whereDate('task_sessions.created_at', '>=', $fromDate)
                         ->whereDate('task_sessions.created_at', '<=', $toDate);

        if (isset($request->project) && is_array($request->project)) {
            $project = array_filter($request->project);
            if (! empty($project)) {
                $projectQuery->whereIn('projects.id', $request->project);
            }
        }

        if (isset($request->sessionType) && is_array($request->sessionType)) {
            $sessionTypes = array_filter($request->sessionType);
            if (! empty($sessionTypes)) {
                $projectQuery->whereIn('task_sessions.session_type', $request->sessionType);
            }
        }

        if (isset($request->client) && is_array($request->client)) {
            $client = array_filter($request->client);
            if (! empty($client)) {
                $projectQuery->whereIn('projects.client_id', $client);
            }
        }

        $results = $projectQuery->groupBy('projects.project_id', 'projects.project_name', 'projects.id', 'clients.company_name')
                     ->get();

        return $results;
    }

    public static function getAllBillableProjects($request)
    {
        $projectQuery = Project::select(
            'projects.project_name',
            'projects.project_id',
            'projects.id',
        )
        ->join('tasks', 'projects.id', '=', 'tasks.project_id')
        ->join('task_sessions', 'tasks.id', '=', 'task_sessions.task_id')
        ->whereNull('projects.deleted_at');

        if (isset($request->daterange) && $request->daterange != '') {
            $daterange = explode(' - ', $request->daterange);
            $fromDate = Carbon::createFromFormat('d/m/Y', $daterange[0])->format('Y-m-d');
            $toDate = Carbon::createFromFormat('d/m/Y', $daterange[1])->format('Y-m-d');
        } else {
            $fromDate = Carbon::now()->subMonth()->startOfDay()->format('Y-m-d');
            $toDate = Carbon::now()->endOfDay()->format('Y-m-d');
        }
        $projectQuery->whereDate('task_sessions.created_at', '>=', $fromDate)
                         ->whereDate('task_sessions.created_at', '<=', $toDate);

        $results = $projectQuery->groupBy('projects.project_id', 'projects.project_name', 'projects.id')
                     ->get();

        return $results;
    }

    /**
     * Fetches the billable clients based on the given request.
     *
     * @param  mixed  $request  The request object containing the necessary parameters for fetching the billable clients.
     * @return \Illuminate\Support\Collection The collection of billable clients.
     */
    public function fetchBillableClients($request)
    {
        $clientQuery = Client::select(
            'clients.id',
            'clients.company_name'
        )
        ->join('projects', 'projects.client_id', '=', 'clients.id')
        ->join('tasks', 'projects.id', '=', 'tasks.project_id')
        ->join('task_sessions', 'tasks.id', '=', 'task_sessions.task_id')
        ->whereNull('projects.deleted_at');

        if (isset($request->daterange) && $request->daterange != '') {
            $daterange = explode(' - ', $request->daterange);
            $fromDate = Carbon::createFromFormat('d/m/Y', $daterange[0])->format('Y-m-d');
            $toDate = Carbon::createFromFormat('d/m/Y', $daterange[1])->format('Y-m-d');
        } else {
            $fromDate = Carbon::now()->subMonth()->startOfDay()->format('Y-m-d');
            $toDate = Carbon::now()->endOfDay()->format('Y-m-d');
        }

        $clientQuery->whereDate('task_sessions.created_at', '>=', $fromDate)
                        ->whereDate('task_sessions.created_at', '<=', $toDate);

        $results = $clientQuery->groupBy('clients.id', 'clients.company_name')
                    ->get();

        return $results;
    }

    /**
     * Fetches the billable hours for a specific project within a given date range.
     *
     * @param  $request  The request data containing the project ID and optional date range.
     * @return \Illuminate\Support\Collection The collection of project billable hours.
     */
    public function fetchProjectBillableHours($request, $dataDisplayType)
    {
        if (isset($request->dateRange) && $request->dateRange != '') {
            $daterange = explode(' - ', $request->dateRange);
            $fromDate = Carbon::createFromFormat('d/m/Y', $daterange[0])->startOfDay();
            $toDate = Carbon::createFromFormat('d/m/Y', $daterange[1])->endOfDay();
        } else {
            $fromDate = Carbon::now()->startOfWeek();
            $toDate = Carbon::now()->endOfWeek();
        }

        if ($dataDisplayType == 'year') {
            return $this->getDataBasedOnYear($fromDate, $toDate, $request->projectId);
        } elseif ($dataDisplayType == 'week') {
            return $this->getDataBasedOnWeek($fromDate, $toDate, $request->projectId);
        } elseif ($dataDisplayType == 'month') {
            return $this->getDataBasedOnMonth($fromDate, $toDate, $request->projectId);
        } else {
            return $this->getDataBasedOnDay($fromDate, $toDate, $request->projectId);
        }
    }

    protected function getDataBasedOnYear($fromDate, $toDate, $projectId)
    {
        $startYear = $fromDate->year;
        $endYear = $toDate->year;

        $results = TaskSession::select(
            'projects.project_name',
            'projects.project_id',
            'projects.id',
            DB::raw('YEAR(task_sessions.created_at) as date_range'),
            DB::raw('SUM(task_sessions.total) / 60 as total_hours'),
            DB::raw('SUM(task_sessions.billed_today) / 60 as billable_hours')
        )
        ->join('tasks', 'tasks.id', '=', 'task_sessions.task_id')
        ->join('projects', 'projects.id', '=', 'tasks.project_id')
        ->where('projects.id', $projectId)
        ->whereBetween(DB::raw('DATE(task_sessions.created_at)'), [$fromDate->toDateString(), $toDate->toDateString()])
        ->groupBy('projects.project_name', 'projects.project_id', 'projects.id', DB::raw('YEAR(task_sessions.created_at)'))
        ->orderBy('date_range')
        ->get()
        ->keyBy('date_range');

        $combinedResults = [];

        for ($year = $startYear; $year <= $endYear; $year++) {
            if (isset($results[$year])) {
                $combinedResults[] = $results[$year];
            } else {
                $combinedResults[] = [
                    'project_name' => $results->first()->project_name ?? '',
                    'project_id' => $results->first()->project_id ?? $projectId,
                    'id' => $results->first()->id ?? 0,
                    'date_range' => $year,
                    'total_hours' => 0,
                    'billable_hours' => 0
                ];
            }
        }

        return $combinedResults;
    }

    protected function getDataBasedOnDay($fromDate, $toDate, $projectId)
    {
        $results = TaskSession::select(
            'projects.project_name',
            'projects.project_id',
            'projects.id',
            DB::raw('DATE_FORMAT(DATE(task_sessions.created_at), "%d %b %Y") as date_range'),
            DB::raw('SUM(task_sessions.total) / 60 as total_hours'),
            DB::raw('SUM(task_sessions.billed_today) / 60 as billable_hours')
        )
        ->join('tasks', 'tasks.id', '=', 'task_sessions.task_id')
        ->join('projects', 'projects.id', '=', 'tasks.project_id')
        ->where('projects.id', $projectId)
        ->whereBetween('task_sessions.created_at', [$fromDate, $toDate])
        ->groupBy('projects.project_name', 'projects.project_id', 'projects.id', DB::raw('DATE_FORMAT(DATE(task_sessions.created_at), "%d %b %Y")'))
        ->orderBy('date_range')
        ->get()
        ->keyBy('date_range');

        $combinedResults = [];

        for ($date = $fromDate->copy(); $date->lte($toDate); $date->addDay()) {
            $dateString = $date->format('d M Y');

            if (isset($results[$dateString])) {
                $combinedResults[] = $results[$dateString];
            } else {
                $combinedResults[] = [
                    'project_name' => $results->first()->project_name ?? '',
                    'project_id' => $results->first()->project_id ?? $projectId,
                    'id' => $results->first()->id ?? 0,
                    'date_range' => $dateString,
                    'total_hours' => 0,
                    'billable_hours' => 0
                ];
            }
        }

        return $combinedResults;
    }

    protected function getDataBasedOnMonth($fromDate, $toDate, $projectId)
    {
        $months = [];
        for ($date = $fromDate->copy()->startOfMonth(); $date->lte($toDate); $date->addMonth()) {
            $months[] = $date->format('Y M');
        }

        // Query for task sessions grouped by month
        $results = TaskSession::select(
            'projects.project_name',
            'projects.project_id',
            'projects.id',
            DB::raw('DATE_FORMAT(task_sessions.created_at, "%Y %b") as date_range'),
            DB::raw('SUM(task_sessions.total)/60 as total_hours'),
            DB::raw('SUM(task_sessions.billed_today)/60 as billable_hours')
        )
        ->join('tasks', 'tasks.id', '=', 'task_sessions.task_id')
        ->join('projects', 'projects.id', '=', 'tasks.project_id')
        ->where('projects.id', $projectId)
        ->whereBetween(DB::raw('DATE(task_sessions.created_at)'), [$fromDate->toDateString(), $toDate->toDateString()])
        ->groupBy('projects.project_name', 'projects.project_id', 'projects.id', 'date_range')
        ->orderBy('date_range')
        ->get();

        // Convert results to an associative array with months as keys
        $resultsByMonth = $results->keyBy('date_range')->toArray();

        // Initialize a new collection to store combined data
        $combinedResults = [];

        foreach ($months as $month) {
            if (isset($resultsByMonth[$month])) {
                $combinedResults[] = $resultsByMonth[$month];
            } else {
                $combinedResults[] = [
                    'project_name' => $results->first()->project_name ?? '',
                    'project_id' => $results->first()->project_id ?? $projectId,
                    'id' => $results->first()->id ?? 0,
                    'date_range' => $month,
                    'total_hours' => 0,
                    'billable_hours' => 0
                ];
            }
        }

        return $combinedResults;
    }

    protected function getDataBasedOnWeek($fromDate, $toDate, $projectId)
    {
        // Generate weeks using PHP's startOfWeek() and endOfWeek()
        $weeks = [];
        $start = $fromDate->copy()->startOfWeek();
        $end = $toDate->copy()->endOfWeek();

        while ($start->lte($end)) {
            $weekStart = $start->copy()->startOfWeek()->format('d M Y');
            $weekEnd = $start->copy()->endOfWeek()->format('d M Y');
            $weeks[] = "$weekStart - $weekEnd";
            $start->addWeek();
        }

        // Fetch data from the database
        $results = TaskSession::select(
            'projects.project_name',
            'projects.project_id',
            'projects.id',
            DB::raw('CONCAT(
            DATE_FORMAT(DATE_SUB(task_sessions.created_at, INTERVAL WEEKDAY(task_sessions.created_at) DAY), "%d %b %Y"),
            " - ",
            DATE_FORMAT(DATE_ADD(DATE_SUB(task_sessions.created_at, INTERVAL WEEKDAY(task_sessions.created_at) DAY), INTERVAL 6 DAY), "%d %b %Y")
        ) as date_range'),
            DB::raw('SUM(task_sessions.total) / 60 as total_hours'),
            DB::raw('SUM(task_sessions.billed_today) / 60 as billable_hours')
        )
            ->join('tasks', 'tasks.id', '=', 'task_sessions.task_id')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->where('projects.id', $projectId)
            ->whereBetween(DB::raw('DATE(task_sessions.created_at)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->groupBy(DB::raw('DATE_SUB(task_sessions.created_at, INTERVAL WEEKDAY(task_sessions.created_at) DAY)'))
            ->orderBy(DB::raw('DATE_SUB(task_sessions.created_at, INTERVAL WEEKDAY(task_sessions.created_at) DAY)'))
            ->get()
            ->keyBy('date_range');

        $combinedResults = [];

        // Combine the results with the generated weeks
        foreach ($weeks as $week) {
            if (isset($results[$week])) {
                $combinedResults[] = $results[$week];
            } else {
                // Add zero-filled result for weeks with no data
                $combinedResults[] = [
                    'project_name' => $results->first()->project_name ?? '',
                    'project_id' => $results->first()->project_id ?? $projectId,
                    'id' => $results->first()->id ?? 0,
                    'date_range' => $week,
                    'total_hours' => 0,
                    'billable_hours' => 0
                ];
            }
        }

        return $combinedResults;
    }
}
