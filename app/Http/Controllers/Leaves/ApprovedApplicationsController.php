<?php

namespace App\Http\Controllers\Leaves;

use App\Http\Controllers\Controller;
use App\Services\LeaveService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ApprovedApplicationsController extends Controller
{
    use GeneralTrait;

    private $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function previousApplications()
    {
        $users = $this->leaveService->getUsers();
        $userType = '';
        $leaveTypes = $this->leaveService->getLeaveTypes();

        return view('leave.adminleave.previous-applications', compact('users', 'userType', 'leaveTypes'));
    }

    public function listPreviousApplications(Request $request)
    {
        $dates = $this->leaveService->getDates($request);
        $holidays = $this->leaveService->getHolidays($request);
        $applications = $this->leaveService->getPreviousApplications($request->user, $request->year, $request->month, $request->userType, $request->leaveType);
        $applicationLists = $this->leaveService->getPreviousApplicationsList($request->user, $request->year, $request->month, $request->userType, $request->leaveType);

        $content = view('leave.adminleave.previous-list', compact('dates', 'applications', 'applicationLists', 'holidays'))->render();

        $res = [
            'data' => $content,
            'status' => 'success'
        ];

        return response()->json($res);
    }
}
