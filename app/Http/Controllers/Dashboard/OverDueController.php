<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class OverDueController extends Controller
{
    private $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function getOverdueProjects(Request $request)
    {
        $draw = $request->get('draw');
        $totalRecords = Project::returnOverdueProjects()->count();
        $totalRecordswithFilter = Project::returnOverdueProjects($request)->count();
        $overdueProjects = Project::returnOverdueProjects($request);
        $overdueProjectsList = $this->dashboardService->setOverdueProjectsList($overdueProjects, $request);
        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $this->dashboardService->formatOverdueProjectsList($overdueProjectsList)
        ];

        return response()->json($response);
    }

    public function getOverdueTasks(Request $request)
    {
        $draw = $request->get('draw');
        $totalRecords = Task::getOverdueTask()->count();
        $totalRecordswithFilter = Task::getOverdueTask($request)->count();
        $overdueTasks = Task::getOverdueTask($request);
        $overdueTasksList = $this->dashboardService->setOverdueTasksList($overdueTasks, $request);
        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $this->dashboardService->formatOverdueTasksList($overdueTasksList)
        ];

        return response()->json($response);
    }
}
