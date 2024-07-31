<?php

namespace App\Http\Controllers\Leaves;

use App\Http\Controllers\Controller;
use App\Services\LeaveService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class AssignLeaveController extends Controller
{
    use GeneralTrait;

    private $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function getUserLeaveApplications(Request $request)
    {
        $userId = $request->user_id;
        $allLeaves = $this->leaveService->getAllLeaves($userId, $this->getYear());
        $balance = $this->leaveService->getUserLeaveBalanceCount($userId);

        $content = view('leave.assignLeave.list', compact('allLeaves', 'balance'))->render();
        $res = [
            'status' => 'true',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assignLeave()
    {
        $users = $this->leaveService->getUsers();
        $allLeaves = [];
        $balance = $this->leaveService->getBalance($this->getLeaves());

        return view('leave.assignLeave.index', compact('allLeaves', 'balance', 'users'));
    }
}
