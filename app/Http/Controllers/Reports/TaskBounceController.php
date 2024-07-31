<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;

class TaskBounceController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $users = User::mailableEmployees()->select('id', 'first_name', 'last_name')->get();
        $projects = Project::select('id', 'project_name')->get();
        $date = $this->reportService->getDateForTaskBounceIndex();

        return view('task-bounds.index', compact('users', 'projects', 'date'));
    }

    public function taskBounceSearch(Request $request)
    {
        $tasks = $this->reportService->getTasksForTaskBounceReport($request);
        $content = view('task-bounds.sheet', compact('tasks'))->render();
        $res = [
            'data' => $content,
            'status' => 'success'
        ];

        return response()->json($res);
    }

    public function report()
    {
        $date = $this->reportService->getDateForTaskBounceReport();

        return view('task-bounds.reports', compact('date'));
    }

    public function bounceReportSearch(Request $request)
    {
        $reports = $this->reportService->getBounceReport($request);
        $content = view('task-bounds.reports-sheet', compact('reports'))->render();
        $res = [
            'data' => $content,
            'status' => 'success'
        ];

        return response()->json($res);
    }

    public function graph()
    {
        $userId = '';
        $date = $this->reportService->getDateForTaskBounceReport();
        $users = $this->reportService->getUserRejections($date, $userId)->pluck('full_name', 'id');

        return view('task-bounds.graph', compact('users', 'date'));
    }

    public function setChartData(Request $request)
    {
        $dateRange = $request->daterange;
        $userId = $request->userId;
        $reports = $this->reportService->getUserRejections($dateRange, $userId);
        $users = $this->reportService->getRejectionUsersList($reports, $userId);

        $usersRejected = [];
        $rejectCountData = [];
        $count = 0;
        foreach ($reports as $report) {
            if ($report->total_rejections > 0) {
                $count++;
                $usersRejected[] = $report->full_name;
                $rejectCountData[] = $report->total_rejections;
            }
        }
        if ($count <= 0) {
            return response()->json(['error' => 'No Rejection Records For Graphical Representation!', 'users' => $users]);
        }

        return response()->json(['bounce' => $rejectCountData, 'months' => $usersRejected, 'users' => $users]);
    }
}
