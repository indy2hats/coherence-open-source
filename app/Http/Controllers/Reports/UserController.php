<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function userReport()
    {
        $date = $this->reportService->getDateForReport();

        return view('reports.users.index', compact('date'));
    }

    public function userReportSearch(Request $request)
    {
        $users = $this->reportService->getUsers($request->daterange);

        $content = view('reports.users.sheet', compact('users'))->render();
        $res = [
            'data' => $content,
            'status' => 'success'
        ];

        return response()->json($res);
    }

    public function userAccountReport(Request $request)
    {
        if ($request->ajax()) {
            $users = User::active()->orderBy('first_name', 'Asc');

            return $this->reportService->userAccountReport($users, $request->get('status'), $request->get('search'));
        }

        return view('reports.users.accounts-report');
    }
}
