<?php

namespace App\Http\Controllers\Leaves\Apply;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Services\LeaveService;
use App\Traits\GeneralTrait;
use Auth;
use Illuminate\Http\Request;

class ApplyLeaveController extends Controller
{
    use GeneralTrait;

    private $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = $this->getYear();
        $allLeaves = [];
        $leaves = $this->getLeaves();
        $balance = $this->leaveService->getInitialLeavesCount($this->getCurrentUserId());

        return view('leave.index', compact('date', 'allLeaves', 'balance'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date_format:d/m/Y',
            'to_date' => 'required|date_format:d/m/Y',
            'type' => 'required',
            'session' => 'required',
            'reason' => 'required',
        ]);

        return $this->leaveService->store($request);
    }

    public function getLeaveApplications()
    {
        $role = $this->getCurrentUser()->role->name;
        $content = $this->leaveService->getLeaveListingByRole($role);
        $res = [
            'status' => 'true',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function destroy($id)
    {
        $this->deleteLeave($id);

        return response()->json(['message' => 'Leave deleted successfully']);
    }

    public function edit($id)
    {
        $leave = $this->findLeaveById($id);
        $balance = $this->leaveService->getUserLeaveBalanceCount($leave->user_id);

        $content = view('leave.adminleave.edit', compact('leave', 'balance'))->render();
        $res = [
            'data' => $content,
            'status' => 'success'
        ];

        return response()->json($res);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'from_date' => 'required|date_format:d/m/Y',
            'to_date' => 'required|date_format:d/m/Y',
            'type' => 'required',
            'session' => 'required',
            'reason' => 'required',
            'status' => 'required',
        ]);

        return $this->leaveService->update($id, $request);
    }

    public function getLeave($code)
    {
        $arr = explode('.', $code);
        $emailCode = $arr[0];
        $userId = $arr[1];

        $leave = Leave::whereEmailCode($emailCode)->first();

        if (! $leave) {
            abort(404);
        }

        if (! Auth::check()) {
            abort(401, 'Cannot perform this action without logging in');
        }

        if ($userId != $this->getCurrentUserId()) {
            abort(403);
        }

        if (! $this->getCurrentUser()->can('manage-leave')) {
            abort(403, 'You dont have rights to access this page');
        }

        return view('leave.get-leave', compact('leave', 'userId'));
    }

    public function cancelLeave($id)
    {
        $this->updateLeave($id, ['status' => 'Cancelled']);
        $leave = $this->findLeaveById($id);
        $this->leaveService->sendLeaveCancelledNotification($leave);

        return response()->json(['message' => 'Leave cancelled succesfully']);
    }

    public function markAsLop()
    {
        $this->updateLeave(request('id'), ['lop' => request('lop')]);

        return response()->json(['message' => 'Changed LOP status successfully']);
    }
}
