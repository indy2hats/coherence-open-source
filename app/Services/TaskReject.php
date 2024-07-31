<?php

namespace App\Services;

use App\Models\Task;

class TaskReject
{
    public function rejectTaskCompletion($task_id)
    {
        Task::find($task_id)->update(['status' => 'In Progress', 'percent_complete' => 20]);

        return true;
    }
}
