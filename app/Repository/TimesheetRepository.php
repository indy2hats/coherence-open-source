<?php

namespace App\Repository;

use App\Models\TaskSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TimesheetRepository
{
    public static function getCsvTimesheetData($request)
    {
        $filterDate = $request['filterDate'];
        $monthDate = $request['monthDate'];
        $clientId = $request['clientId'];
        $userType = $request['userType'];
        $sessionType = $request['sessionType'];
        $projectCategory = $request['projectCategory'];
        $projectId = $request['projectId'];
        $userId = $request['userId'];
        $daysType = $request['daysType'];
        $date = $request['date'];

        $split = explode(' - ', $date);
        $dateMonth = '';
        if ($date) {
            $startDate = Carbon::createFromFormat('d/m/Y', $split[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $split[1])->format('Y-m-d');
        }
        if ($monthDate) {
            $convertDate = Carbon::createFromFormat('F / Y', $filterDate)->startOfMonth()->format('Y-m-d');
            $startDate = Carbon::createFromFormat('Y-m-d', $convertDate)->startOfMonth()->toDateTimeString();
            $endDate = Carbon::createFromFormat('Y-m-d', $convertDate)->endOfMonth()->toDateTimeString();
            $dateMonth = Carbon::createFromFormat('Y-m-d', $convertDate)->format('F / Y');
        }

        return TaskSession::join('tasks', 'tasks.id', '=', 'task_sessions.task_id')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->when(! empty($projectId), function ($query) use ($projectId) {
                $query->where('tasks.project_id', $projectId);
            })->when(! empty($clientId), function ($query) use ($clientId) {
                $query->whereHas('task.project', function ($q) use ($clientId) {
                    $q->where('client_id', $clientId);
                });
            })->when(! empty($projectCategory), function ($query) use ($projectCategory) {
                $query->whereHas('task.project', function ($q) use ($projectCategory) {
                    $q->where('category', $projectCategory);
                });
            })
            ->when(! empty($userId), function ($query) use ($userId) {
                $query->whereIn('user_id', $userId);
            })
            ->when($userType == '1', function ($query) {
                return $query->contractUsers();
            })->when($userType == '2', function ($query) {
                return $query->nonContractUsers();
            })->when(! empty($sessionType), function ($query) use ($sessionType) {
                return $query->whereIn('session_type', $sessionType);
            })
            ->when(! empty($date), function ($q) use ($date) {
                $split = explode(' - ', $date);
                $startDate = Carbon::createFromFormat('d/m/Y', $split[0])->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', $split[1])->format('Y-m-d');

                return $q->whereDate('task_sessions.created_at', '>=', $startDate)->whereDate('task_sessions.created_at', '<=', $endDate);
            })
            ->when(! empty($filterDate), function ($q) use ($filterDate) {
                $convertDate = Carbon::createFromFormat('F / Y', $filterDate)->startOfMonth()->format('Y-m-d');
                $startDate = Carbon::createFromFormat('Y-m-d', $convertDate)->startOfMonth()->toDateTimeString();
                $endDate = Carbon::createFromFormat('Y-m-d', $convertDate)->endOfMonth()->toDateTimeString();

                return $q->whereDate('task_sessions.created_at', '>=', $startDate)->whereDate('task_sessions.created_at', '<=', $endDate);
            })
            ->when($daysType == 'non-workdays', function ($query) {
                $query->where(function ($query) {
                    $query->whereRaw('WEEKDAY(task_sessions.created_at) > 4');
                    $query->orWhereIn(DB::raw('DATE_FORMAT(task_sessions.created_at,"%Y-%m-%d")'), function ($sql) {
                        $sql->selectRaw('DATE_FORMAT(holiday_date,"%Y-%m-%d")')->from('holidays')->get();
                    });
                });
            })
            ->select('title as task', 'projects.project_name', 'task_sessions.created_at as date', DB::raw('total as hours'), DB::raw('billed_today as billed_hours'), 'comments',
                'tasks.description as taskDescription', 'tasks.actual_estimated_time as taskEstimatedTime',
                'tasks.task_url', 'tasks.id as taskId')
            ->whereNull('projects.deleted_at')
            ->whereNull('tasks.deleted_at')
            ->whereNull('projects.deleted_at')
            ->orderBy('projects.id', 'DESC')
            ->orderBy('task_sessions.task_id', 'DESC')
            ->orderBy('task_sessions.created_at', 'DESC')
            ->get()
            ->toArray();
    }

    public static function totalTimeForTask($request)
    {
        $clientId = $request['clientId'];
        $userType = $request['userType'];
        $sessionType = $request['sessionType'];
        $projectCategory = $request['projectCategory'];
        $projectId = $request['projectId'];
        $userId = $request['userId'];
        $daysType = $request['daysType'];

        $taskTimes = TaskSession::join('tasks', 'tasks.id', '=', 'task_sessions.task_id')
        ->join('projects', 'projects.id', '=', 'tasks.project_id')
        ->when(! empty($projectId), function ($query) use ($projectId) {
            $query->where('tasks.project_id', $projectId);
        })
        ->when(! empty($clientId), function ($query) use ($clientId) {
            $query->whereHas('task.project', function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        })
        ->when(! empty($projectCategory), function ($query) use ($projectCategory) {
            $query->whereHas('task.project', function ($q) use ($projectCategory) {
                $q->where('category', $projectCategory);
            });
        })
        ->when(! empty($userId), function ($query) use ($userId) {
            $query->whereIn('user_id', $userId);
        })
        ->when($userType == '1', function ($query) {
            return $query->contractUsers();
        })
        ->when($userType == '2', function ($query) {
            return $query->nonContractUsers();
        })
        ->when(! empty($sessionType), function ($query) use ($sessionType) {
            return $query->whereIn('session_type', $sessionType);
        })
        ->when($daysType == 'non-workdays', function ($query) {
            $query->where(function ($query) {
                $query->whereRaw('WEEKDAY(task_sessions.created_at) > 4');
                $query->orWhereIn(DB::raw('DATE_FORMAT(task_sessions.created_at,"%Y-%m-%d")'), function ($sql) {
                    $sql->selectRaw('DATE_FORMAT(holiday_date,"%Y-%m-%d")')->from('holidays')->get();
                });
            });
        })
        ->select(
            DB::raw('SUM(task_sessions.total) as totalTimeTaken'),
            DB::raw('SUM(task_sessions.billed_today) as billedTotal'),
            'tasks.id as taskId'
        )
        ->whereNull('projects.deleted_at')
        ->whereNull('tasks.deleted_at')
        ->groupBy('task_sessions.task_id', 'tasks.id')
        ->orderBy('projects.id', 'DESC')
        ->orderBy('task_sessions.task_id', 'DESC')
        ->orderBy('task_sessions.created_at', 'DESC')
        ->get()
        ->keyBy('taskId')
        ->map(function ($item) {
            return [
                'totalTimeTaken' => $item->totalTimeTaken ?? 0,
                'billedTotal' => $item->billedTotal ?? 0,
            ];
        })
        ->toArray();

        return $taskTimes;
    }
}
