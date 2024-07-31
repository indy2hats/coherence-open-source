<?php

namespace App\Repository;

use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;

class TeamTimesheetRepository
{
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function getTeamTimesheet($userId, $filter = null)
    {
        return Team::where('reporting_to', $userId)
            ->when(isset($filter['user_id']) && $filter['user_id'] != '', function ($query) use ($filter) {
                return $query->where('reportee', $filter['user_id']);
            })
            ->with(['reportee_user:id,first_name,last_name', 'reportee_user.users_task_session' => function ($query) use ($filter) {
                $date = isset($filter['date']) ? Carbon::createFromFormat('d/m/Y', $filter['date'])->format('Y-m-d') : Carbon::today()->format('Y-m-d');
                $query->selectRaw('user_id, COUNT(DISTINCT task_id) as task_count, SUM(total) as total_time')
                    ->where('created_at', 'like', "%$date%");

                $query->groupBy('user_id');
            }])
            ->get();
    }
}
//$date = Carbon::parse($filter['date'])->format('Y-m-d') ?? Carbon::today()->format('Y-m-d');
