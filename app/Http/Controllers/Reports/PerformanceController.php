<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function performanceReport()
    {
        $date = $this->reportService->getDateForReport();

        return view('reports.performance.index', compact('date'));
    }

    public function employeePerformanceSearch(Request $request)
    {
        $dateRange = $request->daterange;

        $fromDate = null;
        $toDate = null;

        if ($dateRange != '') {
            $daterange = explode(' - ', $dateRange);
            $fromDate = Carbon::createFromFormat('d/m/Y', $daterange[0])->format('Y-m-d');
            $toDate = Carbon::createFromFormat('d/m/Y', $daterange[1])->format('Y-m-d');
        }

        $workingDays = $this->reportService->getWorkingDays($fromDate, $toDate);
        $users = $this->reportService->getUsersForEmployeePerformanceSearch($fromDate, $toDate);

        $content = view('reports.performance.sheet', compact('workingDays', 'users', 'fromDate', 'toDate'))->render();

        $res = [
            'data' => $content,
            'status' => 'success'
        ];

        return response()->json($res);
    }
}
