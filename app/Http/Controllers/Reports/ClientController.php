<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Generate the client report.
     *
     * This function retrieves the date for the report from the report service
     * and then returns a view for the client report, passing the date as a
     * compact variable.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clientReport()
    {
        $date = $this->reportService->getDateForReport();

        return view('reports.clients.index', compact('date'));
    }

    public function clientSearch(Request $request)
    {
        $clients = $this->reportService->getClients($request->daterange);
        $content = view('reports.clients.sheet', compact('clients'))->render();
        $res = [
            'data' => $content,
            'status' => 'success'
        ];

        return response()->json($res);
    }
}
