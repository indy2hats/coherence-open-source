<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use DB;
use Illuminate\Support\Carbon;

class TimesheetController extends Controller
{
    public function clientMonthly()
    {
        return view('timesheets.client-monthly.index');
    }

    public function userMonthly()
    {
        $projects = Project::orderBy('project_name', 'ASC')->get();

        $users = User::orderBy('first_name', 'ASC')->get();

        $data = [
            'title' => 'Event1',
            'start' => '2020-03-04'
        ];

        $data = json_encode($data);

        return view('timesheets.user-monthly.index', compact('projects', 'users', 'data'));
    }

    public function userMonthSearch()
    {
        $res['data'] = [
            'title' => 'new',
            'start' => date('y,m,d')
        ];

        return response()->json($res);
    }

    public function userDaterange()
    {
        $dataset = [];

        $date = Carbon::now()->startOfMonth()->format('d/m/Y').' - '.Carbon::now()->endOfMonth()->format('d/m/Y');

        $employee_name = '';

        $total = [];

        $users = User::notClients()->active()->orderBy('first_name', 'ASC')->get();

        return view('timesheets.user-daterange.index', compact('users', 'dataset', 'date', 'employee_name', 'total'));
    }

    public function userDaterangeSearch()
    {
        $userId = request('userId');

        $employee_name = User::find($userId)->full_name;

        $daterange = request('daterange');

        $split = explode(' - ', $daterange);

        $startDate = Carbon::createFromFormat('d/m/Y', $split[0])->format('Y-m-d');

        $endDate = Carbon::createFromFormat('d/m/Y', $split[1])->format('Y-m-d');

        $time = [];

        $tasks = DB::select('select task_sessions.created_at,task_sessions.billed_today,task_sessions.total,projects.project_name,tasks.title from task_sessions,projects,tasks WHERE user_id = ? and task_sessions.created_at BETWEEN ? and ? and task_sessions.task_id=tasks.id and projects.id=tasks.project_id ORDER BY `created_at` ASC ', [$userId, $startDate, $endDate]);

        $users = User::active()->orderBy('first_name', 'ASC')->get();

        $date = request('daterange');

        $dataset = $this->createDataset($tasks);

        $total = $this->findTotal($tasks);

        $content = view('timesheets.user-daterange.view', compact('users', 'dataset', 'date', 'employee_name', 'total'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function createDataset($tasks)
    {
        $dataset = [];

        $project_name = null;
        $task_title = null;
        $created_at = null;

        foreach ($tasks as $task) {
            $data = [];
            if (date('d/m/Y', strtotime($task->created_at)) == $created_at) {
                $data += ['created_at' => ''];
            } else {
                $data += ['created_at' => date('d/m/Y', strtotime($task->created_at))];
                $created_at = date('d/m/Y', strtotime($task->created_at));
                $project_name = null;
                $task_title = null;
            }
            if ($task->project_name == $project_name) {
                $data += ['project_name' => ''];
            } else {
                $data += ['project_name' => $task->project_name];
                $project_name = $task->project_name;
            }
            if ($task->title == $task_title) {
                $data += ['title' => ''];
            } else {
                $data += ['title' => $task->title];
                $task_title = $task->title;
            }
            $data += ['total' => $task->total, 'billed' => $task->billed_today];

            array_push($dataset, $data);
        }

        return $dataset;
    }

    public function findTotal($tasks)
    {
        $total = ['time_spent' => 0, 'billed' => 0];
        foreach ($tasks as $task) {
            $total['time_spent'] += $task->total;
            $total['billed'] += $task->billed_today;
        }

        return $total;
    }

    public function projectDaterange()
    {
        $dataset = [];

        $date = Carbon::now()->startOfMonth()->format('d/m/Y').' - '.Carbon::now()->endOfMonth()->format('d/m/Y');

        $project_name = '';

        $total = [];

        $projects = Project::orderBy('project_name', 'ASC')->get();

        $users = User::orderBy('first_name', 'ASC')->get();

        return view('timesheets.project-daterange.index', compact('projects', 'dataset', 'date', 'project_name', 'total', 'users'));
    }

    public function projectDaterangeSearch()
    {
        $projectId = request('projectId');

        $userId = request('userId');

        $project_name = Project::find($projectId)->project_name;

        $daterange = request('daterange');

        $split = explode(' - ', $daterange);

        $startDate = Carbon::createFromFormat('d/m/Y', $split[0])->format('Y-m-d');

        $endDate = Carbon::createFromFormat('d/m/Y', $split[1])->format('Y-m-d');

        $time = [];

        $tasks = '';

        if ($userId != '') {
            $tasks = DB::select('select task_sessions.created_at,projects.id,projects.project_name, tasks.title, users.first_name,users.last_name, task_sessions.billed_today,task_sessions.total FROM task_sessions,projects,tasks,users WHERE projects.id=? and users.id=task_sessions.user_id and task_sessions.user_id=? and tasks.id=task_sessions.task_id and projects.id=tasks.project_id and task_sessions.created_at BETWEEN ? AND ? ORDER BY task_sessions.created_at ASC ', [$projectId, $userId, $startDate, $endDate]);
        } else {
            $tasks = DB::select('select task_sessions.created_at,projects.id,projects.project_name, tasks.title, users.first_name,users.last_name, task_sessions.billed_today,task_sessions.total FROM task_sessions,projects,tasks,users WHERE projects.id=? and users.id=task_sessions.user_id and tasks.id=task_sessions.task_id and projects.id=tasks.project_id and task_sessions.created_at BETWEEN ? AND ? ORDER BY task_sessions.created_at ASC ', [$projectId, $startDate, $endDate]);
        }

        $projects = Project::orderBy('project_name', 'ASC')->get();

        $users = User::orderBy('first_name', 'ASC')->get();

        $date = request('daterange');

        $dataset = $this->createDatasetProject($tasks);

        $total = $this->findTotalProject($tasks);

        $content = view('timesheets.project-daterange.view', compact('projects', 'dataset', 'date', 'project_name', 'total', 'users'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function createDatasetProject($tasks)
    {
        $dataset = [];

        $user_name = null;
        $task_title = null;
        $created_at = null;

        foreach ($tasks as $task) {
            $data = [];
            if (date('d/m/Y', strtotime($task->created_at)) == $created_at) {
                $data += ['created_at' => ''];
            } else {
                $data += ['created_at' => date('d/m/Y', strtotime($task->created_at))];
                $created_at = date('d/m/Y', strtotime($task->created_at));
                $user_name = null;
                $task_title = null;
            }
            if ($task->first_name.' '.$task->last_name == $user_name) {
                $data += ['user_name' => ''];
            } else {
                $data += ['user_name' => $task->first_name.' '.$task->last_name];
                $user_name = $task->first_name.' '.$task->last_name;
            }
            if ($task->title == $task_title) {
                $data += ['title' => ''];
            } else {
                $data += ['title' => $task->title];
                $task_title = $task->title;
            }
            $data += ['total' => $task->total, 'billed' => $task->billed_today];

            array_push($dataset, $data);
        }

        return $dataset;
    }

    public function findTotalProject($tasks)
    {
        $total = ['time_spent' => 0, 'billed' => 0];
        foreach ($tasks as $task) {
            $total['time_spent'] += $task->total;
            $total['billed'] += $task->billed_today;
        }

        return $total;
    }

    public function clientDaterange()
    {
        $dataset = [];

        $date = Carbon::now()->startOfMonth()->format('d/m/Y').' - '.Carbon::now()->endOfMonth()->format('d/m/Y');

        $client_name = '';

        $total = [];

        $clients = Client::orderBy('company_name', 'ASC')->get();

        return view('timesheets.client-daterange.index', compact('clients', 'dataset', 'date', 'client_name', 'total'));
    }

    public function clientDaterangeSearch()
    {
        $clientId = request('clientId');

        $client_name = Client::find($clientId)->company_name;

        $daterange = request('daterange');

        $split = explode(' - ', $daterange);

        $startDate = Carbon::createFromFormat('d/m/Y', $split[0])->format('Y-m-d');

        $endDate = Carbon::createFromFormat('d/m/Y', $split[1])->format('Y-m-d');

        $time = [];

        $tasks = DB::select('select task_sessions.created_at,projects.id,projects.project_name,tasks.title, task_sessions.billed_today,task_sessions.total FROM task_sessions,projects,tasks,clients where clients.id=? and clients.id=projects.client_id and tasks.id=task_sessions.task_id and projects.id=tasks.project_id and task_sessions.created_at BETWEEN ? AND ? ORDER BY task_sessions.created_at ASC', [$clientId, $startDate, $endDate]);

        $clients = Client::orderBy('company_name', 'ASC')->get();

        $date = request('daterange');

        $dataset = $this->createDatasetClient($tasks);

        $total = $this->findTotalProject($tasks);

        $content = view('timesheets.client-daterange.view', compact('clients', 'dataset', 'date', 'client_name', 'total'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function createDatasetClient($tasks)
    {
        $dataset = [];

        $project_name = null;
        $task_title = null;
        $created_at = null;

        foreach ($tasks as $task) {
            $data = [];
            if (date('d M Y', strtotime($task->created_at)) == $created_at) {
                $data += ['created_at' => ''];
            } else {
                $data += ['created_at' => date('d M Y', strtotime($task->created_at))];
                $created_at = date('d M Y', strtotime($task->created_at));
                $project_name = null;
                $task_title = null;
            }
            if ($task->project_name == $project_name) {
                $data += ['project_name' => ''];
            } else {
                $data += ['project_name' => $task->project_name];
                $project_name = $task->project_name;
            }
            if ($task->title == $task_title) {
                $data += ['title' => ''];
            } else {
                $data += ['title' => $task->title];
                $task_title = $task->title;
            }
            $data += ['total' => $task->total, 'billed' => $task->billed_today];

            array_push($dataset, $data);
        }

        return $dataset;
    }

    public function findTotalClient($tasks)
    {
        $total = ['time_spent' => 0, 'billed' => 0];
        foreach ($tasks as $task) {
            $total['time_spent'] += $task->total;
            $total['billed'] += $task->billed_today;
        }

        return $total;
    }
}
