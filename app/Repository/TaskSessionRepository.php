<?php

namespace App\Repository;

use App\Models\TaskSession;
use Carbon\Carbon;

class TaskSessionRepository
{
    public static function getCsvTaskSessionData($request)
    {
        $userId = $request->userId;
        $type = $request->type;
        $taskId = $request->taskId;
        $daterange = $request->daterange;

        return TaskSession::with('user')->where('task_id', $taskId)
                                        ->when(! empty($userId), function ($query) use ($userId) {
                                            return $query->where('user_id', $userId);
                                        })
                                        ->when(! empty($type), function ($query) use ($type) {
                                            return $query->where('session_type', $type);
                                        })
                                        ->when(! empty($daterange), function ($q) use ($daterange) {
                                            $split = explode(' - ', $daterange);
                                            $startDate = Carbon::createFromFormat('M d, Y', $split[0])->format('Y-m-d');
                                            $endDate = Carbon::createFromFormat('M d, Y', $split[1])->format('Y-m-d');

                                            return $q->whereBetween('created_at', [$startDate, $endDate]);
                                        })
                                        ->orderByDesc('created_at')
                                        ->get();
    }
}
