<?php

namespace App\Http\Controllers\Leaves;

use App\Http\Controllers\Controller;
use App\Services\LeaveService;
use App\Traits\GeneralTrait;

class PendingApplicationsController extends Controller
{
    use GeneralTrait;

    private $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex()
    {
        return view('leave.adminleave.index');
    }

    public function acceptLeave()
    {
        $leave = $this->leaveService->acceptLeave();
        $this->leaveService->sendLeaveApprovedNotification($leave);

        return response()->json(['message' => 'Leave approved succesfully']);
    }

    public function rejectLeave()
    {
        request()->validate([
            'reason' => 'required',
        ]);

        $leave = $this->leaveService->rejectLeave();
        $this->leaveService->sendLeaveRejectedNotification($leave);

        return response()->json(['message' => 'Leave rejected succesfully']);
    }

    public function getPendingApplications()
    {
        $pendingList = $this->leaveService->pendingApplications();

        $content = view('leave.adminleave.list', compact('pendingList'))->render();
        $res = [
            'status' => 'true',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function getRemainigLeaves()
    {
        $balance = $this->leaveService->getUserLeaveBalanceCount(request('user_id'));

        return view('leave.adminleave.viewremainingleave', compact('balance'));
    }
}
