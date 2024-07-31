<?php

namespace App\Repository;

use App\Models\Branch;

class BranchRepository
{
    public function getAllBranches($taskId = null)
    {
        return Branch::when($taskId, function ($query) use ($taskId) {
            return $query->where('task_id', $taskId);
        })->get();
    }
}
