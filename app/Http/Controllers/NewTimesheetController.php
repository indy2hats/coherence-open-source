<?php

namespace App\Http\Controllers;

use App\Exports\TimesheetExport;
use App\Models\Client;
use App\Models\Project;
use App\Models\SessionType;
use App\Models\TaskSession;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NewTimesheetController extends Controller
{
    public $pagination;

    public function __construct()
    {
        $this->pagination = config('general.pagination');
    }

    public function index()
    {
        $dataset = $userId = [];
        $date = '';
        $dateMonth = '';
        $project_name = '';
        $total = [];
        $userType = '';
        $sessionType = [];
        $projects = Project::orderBy('project_name', 'ASC')->get();
        $users = User::orderBy('first_name', 'ASC')->notClients()->active()->get();
        $clients = Client::orderBy('company_name', 'ASC')->get();
        $sessionTypes = SessionType::pluck('title', 'slug');
        $tasks = TaskSession::with('task', 'user', 'task.project')->has('task.project')->has('task');
        $monthDate = Carbon::now()->format('F / Y');
        $tasks = $tasks->when(! empty($monthDate), function ($q) use ($monthDate) {
            $convertDate = Carbon::createFromFormat('F / Y', $monthDate)->startOfMonth()->format('Y-m-d');
            $startDate = Carbon::createFromFormat('Y-m-d', $convertDate)->startOfMonth()->toDateTimeString();
            $endDate = Carbon::createFromFormat('Y-m-d', $convertDate)->endOfMonth()->toDateTimeString();

            return $q->whereDate('task_sessions.created_at', '>=', $startDate)->whereDate('task_sessions.created_at', '<=', $endDate);
        });
        $dateMonth = Carbon::now()->format('F / Y');
        $tasks = $tasks->orderBy('created_at', 'DESC')->get();

        $dataset = $this->createDatasetProject($tasks);
        $total = $this->findTotalProject($tasks);

        return view('timesheets.new-timesheet.index', compact('clients', 'projects', 'dataset', 'date', 'project_name', 'total', 'users', 'dateMonth', 'userId', 'userType', 'sessionTypes', 'sessionType', 'tasks'));
    }

    public function projectDaterangeSearch()
    {
        $monthDate = request('date');
        $clientId = request('clientId');
        $userType = request('userType');
        $sessionType = request('sessionType');
        $projectCategory = request('projectCategory');
        if ($clientId) {
            $client_name = Client::find($clientId)->company_name;
        }
        $projectId = request('projectId');
        $userId = request('userId');
        $daysType = request('days');
        $project_name = null;
        if ($projectId) {
            $project_name = Project::find($projectId)->project_name;
        }
        $date = request('daterange');
        $split = explode(' - ', $date);
        $dateMonth = '';
        if ($date) {
            $startDate = Carbon::createFromFormat('d/m/Y', $split[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $split[1])->format('Y-m-d');
        }
        if ($monthDate) {
            $convertDate = Carbon::createFromFormat('F / Y', request('date'))->startOfMonth()->format('Y-m-d');
            $startDate = Carbon::createFromFormat('Y-m-d', $convertDate)->startOfMonth()->toDateTimeString();
            $endDate = Carbon::createFromFormat('Y-m-d', $convertDate)->endOfMonth()->toDateTimeString();
            $dateMonth = Carbon::createFromFormat('Y-m-d', $convertDate)->format('F / Y');
        }
        $tasks = TaskSession::with('task', 'user', 'task.project')->has('task')->has('task.project');
        if ($projectId) {
            $tasks = $tasks->whereHas('task', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            });
        }
        if ($clientId) {
            $tasks = $tasks->whereHas('task.project', function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }
        if ($projectCategory) {
            $tasks = $tasks->whereHas('task.project', function ($q) use ($projectCategory) {
                $q->where('category', $projectCategory);
            });
        }
        if ($userId) {
            $tasks = $tasks->whereIn('user_id', $userId);
        } else {
            $userId = [];
        }
        $tasks = $tasks->when($userType == '1', function ($query) {
            return $query->contractUsers();
        })->when($userType == '2', function ($query) {
            return $query->nonContractUsers();
        })->when(! empty($sessionType), function ($query) use ($sessionType) {
            return $query->whereIn('session_type', $sessionType);
        });

        $tasks = $tasks->when(! empty($date), function ($q) use ($date) {
            $split = explode(' - ', $date);
            $startDate = Carbon::createFromFormat('d/m/Y', $split[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $split[1])->format('Y-m-d');

            return $q->whereDate('task_sessions.created_at', '>=', $startDate)->whereDate('task_sessions.created_at', '<=', $endDate);
        })
            ->when(! empty($monthDate), function ($q) use ($monthDate) {
                $convertDate = Carbon::createFromFormat('F / Y', $monthDate)->startOfMonth()->format('Y-m-d');
                $startDate = Carbon::createFromFormat('Y-m-d', $convertDate)->startOfMonth()->toDateTimeString();
                $endDate = Carbon::createFromFormat('Y-m-d', $convertDate)->endOfMonth()->toDateTimeString();

                return $q->whereDate('task_sessions.created_at', '>=', $startDate)->whereDate('task_sessions.created_at', '<=', $endDate);
            });

        if ($daysType == 'non-workdays') {
            $tasks = $tasks->where(function ($query) {
                $query->whereRaw('WEEKDAY(created_at) > 4');
                $query->orWhereIn(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'), function ($sql) {
                    $sql->selectRaw('DATE_FORMAT(holiday_date,"%Y-%m-%d")')->from('holidays')->get();
                });
            });
        }
        $tasks = $tasks->orderBy('created_at', 'DESC')->get();

        $sessionType = $sessionType ?: [];
        $projects = Project::orderBy('project_name', 'ASC')->get();
        $users = User::orderBy('first_name', 'ASC')->notClients()->active()->get();
        $dataset = $this->createDatasetProject($tasks);
        $total = $this->findTotalProject($tasks);
        $clients = Client::orderBy('company_name', 'ASC')->get();
        $sessionTypes = SessionType::pluck('title', 'slug');
        $content = view('timesheets.new-timesheet.view', compact('projects', 'dataset', 'date', 'project_name', 'total', 'users', 'clients', 'dateMonth', 'userId', 'daysType', 'userType', 'sessionTypes', 'sessionType', 'tasks'))->render();

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
        $project_name = null;

        foreach ($tasks as $task) {
            $data = [];
            if (date('d/m/Y', strtotime($task->created_at)) == $created_at) {
                $data += ['created_at' => ''];
            } else {
                $data += ['created_at' => date('d/m/Y', strtotime($task->created_at))];
                $created_at = date('d/m/Y', strtotime($task->created_at));
                $user_name = null;
                $task_title = null;
                $project_name = null;
            }
            if ($task->user->first_name.' '.$task->user->last_name == $user_name) {
                $data += ['user_name' => $user_name];
            } else {
                $data += ['user_name' => $task->user->first_name.' '.$task->user->last_name];
                $user_name = $task->user->first_name.' '.$task->user->last_name;
            }
            if ($task->task) {
                if ($task->task->title == $task_title) {
                    $data += ['title' => ''];
                } else {
                    $data += ['title' => $task->task->title];
                    $task_title = $task->task->title;
                    $project_name = $task->task->project->project_name;
                    $data += ['project_name' => $project_name];
                }
            } else {
                $data += ['title' => ''];
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

    public function exportTimesheet(Request $request)
    {
        $request['filterDate'] = $request->date;
        $request['clientId'] = $request->clientId;
        $request['userType'] = $request->userType;
        $request['sessionType'] = $request->sessionType;
        $request['projectCategory'] = $request->projectCategory;
        $request['projectId'] = $request->projectId;
        $request['userId'] = $request->userId;
        $request['daysType'] = $request->days;
        $request['monthDate'] = $request->date;
        $request['date'] = $request->daterange;
        $response = Excel::download(new TimesheetExport($request), 'Timesheet.xlsx');
        ob_end_clean();

        return $response;
    }
}
