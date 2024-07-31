<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use App\Services\BranchService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    protected $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    /**
     * store.
     *
     * @param  mixed  $request
     * @return
     */
    public function store(BranchRequest $request)
    {
        $this->branchService->addBranch($request);

        return $this->getBranches($request->task_id);
    }

    /**
     * destroy.
     *
     * @param  mixed  $request
     * @return
     */
    public function destroy(Request $request)
    {
        Branch::find($request->id)->delete();

        return $this->getBranches($request->task_id);
    }

    /**
     * getBranches.
     *
     * @param  mixed  $taskId
     * @return
     */
    public function getBranches($taskId)
    {
        $branches = $this->branchService->getAllBranches($taskId);
        $res = [
            'status' => 'success',
            'data' => $branches,
        ];

        return response()->json($res);
    }
}
