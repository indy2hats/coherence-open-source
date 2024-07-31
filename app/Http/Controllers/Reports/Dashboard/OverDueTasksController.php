<?php

namespace App\Http\Controllers\Reports\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class OverDueTasksController extends Controller
{
    /**
     * Display a listing of overdue tasks.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showOverdueTasks()
    {
        $projects = Project::select('id', 'project_name')->orderBy('project_name')->get();
        $clients = Client::orderBy('company_name', 'ASC')->get();

        return view('reports.overduetasks.index', compact('projects', 'clients'));
    }

    /**
     * Search for overdue tasks based on the given request.
     *
     * @param  Request  $request  The request object containing the search criteria.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the rendered view and status.
     */
    public function searchOverdueTasks(Request $request)
    {
        $overduetasks = Task::returnOverdueTask($request);
        $content = view('reports.overduetasks.sheet', compact('overduetasks'))->render();
        $res = [
            'data' => $content,
            'status' => 'success'
        ];

        return response()->json($res);
    }
}
