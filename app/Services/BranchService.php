<?php

namespace App\Services;

use App\Models\Branch;
use App\Repository\BranchRepository;

class BranchService
{
    protected $branchRepository;

    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function addBranch($request)
    {
        $data = [];
        $data = [
            'task_id' => $request->task_id,
            'name' => $request->name,
            'url' => $request->url,
        ];
        Branch::create($data);
    }

    public function getAllBranches($taskId = null)
    {
        return $this->branchRepository->getAllBranches($taskId);
    }
}
