<?php

namespace App\Repository;

use App\Models\Project;
use App\Models\TaskSession;

class ProjectCostRepository
{
    public static function getProjectCostDetails($projectId, $startDate, $endDate, $selectedUserId, $sessionType)
    {
        return Project::where('id', $projectId)->with(['task.tasks_session' => function ($query) use ($startDate, $endDate, $selectedUserId, $sessionType) {
            if (! empty($startDate) && ! empty($endDate)) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            if (! empty($selectedUserId)) {
                $query->where('user_id', $selectedUserId);
            }

            if (! empty($sessionType)) {
                $query->where('session_type', $sessionType);
            }
        }])->first();
    }

    public static function getTotalMinutesWorkedForAMonth($employeeId, $year, $month)
    {
        return TaskSession::where('user_id', $employeeId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('total');
    }
}
