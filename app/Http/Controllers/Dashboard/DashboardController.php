<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskTag;
use App\Services\DashboardService;
use Auth;

class DashboardController extends Controller
{
    private $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $currentUserId = Auth::user()->id;
        $isClient = Auth::user()->hasRole('client');

        if (auth()->user()->can('view-admin-dashboard')) {
            $overduetasks = Task::returnOverdueTask();
            //$overdueprojects = Project::returnOverdueProjects();

            return view('dashboard.index', compact('overduetasks'));
        } elseif ($isClient) {
            $counts = $this->dashboardService->getCountDetails();
            $totalHours = $this->dashboardService->getTotalHours();
            $projects = $this->dashboardService->getProjects($currentUserId);
            $inProgressTasks = [];
            $rejected = [];

            $clientProjects = $projects->pluck('id')->toArray();
            if (! empty($clientProjects)) {
                $inProgressTasks = $this->dashboardService->getInProgressTasksIsClient($clientProjects);
                $rejected = $this->dashboardService->getRejectedTasksIsClient($clientProjects);
            }

            $total = $this->dashboardService->getThisWeek();
            $tags = TaskTag::select('title', 'slug')->orderBy('title')->get();

            return view('dashboard.clientdashboard', compact('counts', 'totalHours', 'inProgressTasks', 'total', 'rejected', 'projects', 'tags'));
        } else {
            $counts = $this->dashboardService->getCountDetails();
            $totalHours = $this->dashboardService->getTotalHours();
            $status = auth()->user()->designation->name == 'Quality Analyst' ? 'Under QA' : 'In Progress';
            $inProgressTasks = $this->dashboardService->getInProgressTasksNotClient($status);
            $total = $this->dashboardService->getThisWeek();
            $rejected = $this->dashboardService->getRejectedTasksNotClient();

            if ($rejected) {
                $rejected = $this->dashboardService->updateRejectedTasks($rejected);
            }

            return view('dashboard.employeedashboard', compact('counts', 'totalHours', 'inProgressTasks', 'total', 'rejected'));
        }
    }
}
