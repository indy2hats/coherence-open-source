<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;

class TaskTimeController extends Controller
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
        $tasks = Task::select('id', 'title')->orderBy('updated_at', 'DESC')->get();
        $date = $this->reportService->getDateForTaskBounceIndex();

        return view('reports.task_time.task-time', compact('users', 'projects', 'date', 'tasks'));
    }

    public function taskTimeSearch(Request $request)
    {
        $taskTimeData = $this->reportService->getTasksTime($request);
        $content = view('reports.task_time.sheet', compact('taskTimeData'))->render();

        $res = [
            'data' => $content,
            'status' => 'success'
        ];

        return response()->json($res);
    }

    public function getTaskTimeUsers()
    {
        if (request('task_id') != '') {
            $id = request('task_id');
            $task = Task::with('users')->where('id', $id)->first();
            if ($task && $task->users) {
                return response()->json(['flag' => true, 'data' => $task->users]);
            }
        } else {
            $users = User::mailableEmployees()->get();
            if ($users) {
                return response()->json(['flag' => true, 'data' => $users]);
            }
        }

        return response()->json(['flag' => false]);
    }
}
