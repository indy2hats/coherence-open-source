<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class EmployeeLeaveController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Shows user leave report index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function userLeaveReport()
    {
        return view('reports.leave.index');
    }

    /**
     *  returns user leave report to the ajax call.
     *
     * @param  mixed  $request
     * @return void
     */
    public function getUserLeaveReport(Request $request)
    {
        $draw = $request->get('draw');
        $totalRecords = $this->reportService->getUsersLeavesCount();
        $totalRecordswithFilter = $this->reportService->getUsersLeavesWithFilterCount($request);
        $users = $this->reportService->getUsersLeaves($request);
        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $this->reportService->formatUsersLeaveList($users)
        ];

        return response()->json($response);
    }
}
