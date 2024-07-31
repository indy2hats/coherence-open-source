<?php

namespace App\Http\Controllers\Reports;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DailyStatusReportRequest;
use App\Models\Holiday;
use App\Services\ReportService;
use Illuminate\Http\Request;

class DailyStatusController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function dailyStatusReport()
    {
        $date = $this->reportService->getDateForDailyStatusReport();

        return view('reports.status_report.index', compact('date'));
    }

    public function dailyStatusReportSearch(Request $request)
    {
        if (empty(Helper::showDailyStatusReportPage())) {
            return abort(403);
        }
        $date = $this->reportService->getDateForDailyStatusReportSearch($request);
        $weekend = $this->reportService->isWeekend($date);
        $holiday = Holiday::where('holiday_date', $date)->first();
        $reports = $this->reportService->getDailyReports($date);
        $formattedDate = date('M d D, Y', strtotime($date));

        $content = view('reports.status_report.sheet', compact('reports', 'holiday', 'date', 'weekend'))->render();

        $res = [
            'data' => $content,
            'status' => 'success',
            'formatted_date' => $formattedDate
        ];

        return response()->json($res);
    }

    public function saveDailyStatusReport(DailyStatusReportRequest $request)
    {
        $this->reportService->createDailyStatusReport($request);
        $res = [
            'status' => 'OK',
            'message' => 'EOD Report Added successfully',
        ];

        return response()->json($res);
    }

    public function updateDailyStatusReport(DailyStatusReportRequest $request)
    {
        $this->reportService->updateUserDetails($request);

        return redirect('/dashboard');
    }

    public function updateDailyStatusReportView()
    {
        if (empty(Helper::showDailyStatusReportPage())) {
            return abort(403);
        }

        $content = $this->reportService->getSessionsForDSR();

        return view('reports.status_report.update', compact('content'));
    }

    public function autofillDailyStatusReport()
    {
        $content = $this->reportService->getSessionsForDSR();
        $res = [
            'status' => 'success',
            'content' => $content,
        ];

        return response()->json($res);
    }
}
