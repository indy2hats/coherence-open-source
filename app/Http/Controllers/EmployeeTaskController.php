<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCredentials;
use App\Models\ProjectDocuments;
use App\Models\Task;
use App\Models\TaskRejection;
use App\Models\TaskSession;
use App\Models\User;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeTaskController extends Controller
{
    /** Display a listing of the resource. */
    public function index()
    {
        $projects = $this->getProjectListOfUser(Auth::user()->id);

        // $projects = Project::orderBy('project_name', 'ASC')->get();
        return view('employeetasks.index', compact('projects'));
    }

    /** Display the specified resource. */
    public function show($id)
    {
        $task = Task::with('project', 'users', 'project.client', 'project.projectUsers')->where('tasks.id', '=', $id)->first();
        $taskSession = DB::select('select task_sessions.id,task_sessions.comments,task_sessions.created_at,total,users.first_name,users.last_name from task_sessions,users where task_sessions.user_id = ? and task_sessions.task_id=? and task_sessions.user_id=users.id order by task_sessions.created_at DESC', [Auth::user()->id, $id]);

        $projectFiles = ProjectDocuments::where('project_id', $task->project_id)->get();

        $projectCredentials = ProjectCredentials::where('project_id', $task->project_id)->get();

        $user = User::with('designation')->where('id', Auth::user()->id)->first();

        $taskRejections = TaskRejection::where('task_id', $id)->where('user_id', Auth::user()->id)->get();

        $taskRejectionStatus = 1;

        $rejectionData = TaskRejection::with('users')->where('task_id', $id)->where('reason', '')->get();

        if ($rejectionData->count() > 0) {
            $taskRejectionStatus = 0;
        }

        return view('employeetasks.viewtasks', compact('task', 'taskSession', 'projectFiles', 'projectCredentials', 'user', 'taskRejections', 'taskRejectionStatus'));
    }

    /** Ajax function to return task session of a user with specified */
    public function getTaskSession()
    {
        $id = request('taskId');

        $task = Task::with('project', 'users', 'project.client', 'project.projectUsers')->where('tasks.id', '=', $id)->first();

        $taskRejections = TaskRejection::where('task_id', $id)->where('user_id', Auth::user()->id)->get();

        $taskSession = DB::select('select task_sessions.id,task_sessions.comments,task_sessions.created_at,total,users.first_name,users.last_name from task_sessions,users where task_sessions.user_id = ? and task_sessions.task_id=? and task_sessions.user_id=users.id order by task_sessions.created_at DESC', [Auth::user()->id, $id]);

        $projectFiles = ProjectDocuments::where('project_id', $task->project_id)->get();

        $projectCredentials = ProjectCredentials::where('project_id', $task->project_id)->get();

        $user = User::with('designation')->where('id', Auth::user()->id)->first();

        $taskRejectionStatus = 1;

        $rejectionData = TaskRejection::with('users')->where('task_id', $id)->where('reason', '')->get();

        if ($rejectionData->count() > 0) {
            $taskRejectionStatus = 0;
        }

        $content = view('employeetasks.show', compact('task', 'taskSession', 'projectFiles', 'projectCredentials', 'user', 'taskRejections', 'taskRejectionStatus'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /** Displays the specified page */
    public function showUpcomingTasks()
    {
        $projects = $this->getProjectListOfUser(Auth::user()->id);

        return view('employeetasks.upcomingtasks', compact('projects'));
    }

    /** Displays the specified page */
    public function showCompletedTasks()
    {
        $projects = $this->getProjectListOfUser(Auth::user()->id);

        return view('employeetasks.completedtasks', compact('projects'));
    }

    public function checkSessionDashboard()
    {
        $session = TaskSession::where('user_id', Auth::user()->id)->where('current_status', 'started')->where('created_at', 'like', '%'.date('Y-m-d').'%')->first();

        if ($session) {
            return response()->json(['data' => $session]);
        }

        return response()->json(['success' => false]);
    }

    public function listOngoingTasks(Request $request)
    {
        $id = Auth::user()->id;

        $ongoingTasks = Task::with(['users' => function ($q) use ($id) {
            $q->where('user_id', $id);
        }, 'user_tasks_session' => function ($q) {
            $q->orderBy('updated_at', 'DESC');
        }
        ])->whereHas('users', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->where('status', '!=', 'Done')->where('status', '!=', 'Backlog')
            ->notArchived()->has('project');

        $ongoingTasks = $this->dataTableTaskSorting($ongoingTasks, $request);

        return Datatables::of($ongoingTasks)
            ->addColumn('created_at_format', function ($task) {
                return $task->created_at_format;
            })->addColumn('current_status', function ($task) {
                return (optional($task->user_tasks_session)->isEmpty()) ? 'NA' : ucfirst($task->user_tasks_session->first()->current_status);
            })->filter(function ($instance) use ($request) {
                if (! empty($request->get('projectId'))) {
                    $instance->where(function ($query) use ($request) {
                        $projectId = $request->get('projectId');
                        $query->where('project_id', $projectId);
                    });
                }
                if (! empty($request->get('search'))) {
                    $instance->where(function ($query) use ($request) {
                        $searchKeyword = $request->get('search');
                        $query->where('title', 'like', '%'.$searchKeyword['value'].'%');
                        $query->orwhere('code', 'like', '%'.$searchKeyword['value'].'%');
                        $query->orwhere('task_url', 'like', '%'.$searchKeyword['value'].'%');
                    });
                }
            })
            ->make(true);
    }

    private function dataTableTaskSorting($tasks, $request)
    {
        $sortColumnIndex = (int) $request->input('order.0.column');
        $sortColumnDir = $request->input('order.0.dir');

        $columns = [
            0 => 'status',
            1 => 'code',
            2 => 'title',
            3 => 'task_id',
            4 => 'percent_complete',
            6 => 'created_at',
            7 => 'updated_at'
        ];
        $sortColumn = in_array($sortColumnIndex, array_keys($columns)) ? $columns[$sortColumnIndex] : 'updated_at';

        return $tasks->orderBy($sortColumn, $sortColumnDir);
    }

    public function listCompletedTasks(Request $request)
    {
        $id = Auth::user()->id;

        $completedTasks = Task::with(['users' => function ($q) use ($id) {
            $q->where('user_id', '=', $id);
        }])->whereHas('users', function ($query) use ($id) {
            $query->where('user_id', '=', $id);
        })->where('status', 'Done')->where('is_archived', 0)->has('project');

        $completedTasks = $this->dataTableTaskSorting($completedTasks, $request);

        return Datatables::of($completedTasks)
            ->addColumn('created_at_format', function ($task) {
                return $task->created_at_format;
            })->filter(function ($instance) use ($request) {
                if (! empty($request->get('projectId'))) {
                    $instance->where(function ($query) use ($request) {
                        $projectId = $request->get('projectId');
                        $query->where('project_id', $projectId);
                    });
                }
                if (! empty($request->get('search'))) {
                    $instance->where(function ($query) use ($request) {
                        $searchKeyword = $request->get('search');
                        $query->where('title', 'like', '%'.$searchKeyword['value'].'%');
                        $query->orwhere('code', 'like', '%'.$searchKeyword['value'].'%');
                        $query->orwhere('task_url', 'like', '%'.$searchKeyword['value'].'%');
                    });
                }
            })
            ->make(true);
    }

    public function listUpcomingTasks(Request $request)
    {
        $id = Auth::user()->id;

        $upcomingTasks = Task::with(['users' => function ($q) use ($id) {
            $q->where('user_id', '=', $id);
        }])->whereHas('users', function ($query) use ($id) {
            $query->where('user_id', '=', $id);
        })->where('status', 'Backlog')->where('is_archived', 0)->has('project');

        $upcomingTasks = $this->dataTableTaskSorting($upcomingTasks, $request);

        return Datatables::of($upcomingTasks)
            ->addColumn('created_at_format', function ($task) {
                return $task->created_at_format;
            })->filter(function ($instance) use ($request) {
                if (! empty($request->get('projectId'))) {
                    $instance->where(function ($query) use ($request) {
                        $projectId = $request->get('projectId');
                        $query->where('project_id', $projectId);
                    });
                }
                if (! empty($request->get('search'))) {
                    $instance->where(function ($query) use ($request) {
                        $searchKeyword = $request->get('search');
                        $query->where('title', 'like', '%'.$searchKeyword['value'].'%');
                        $query->orwhere('code', 'like', '%'.$searchKeyword['value'].'%');
                        $query->orwhere('task_url', 'like', '%'.$searchKeyword['value'].'%');
                    });
                }
            })
            ->make(true);
    }

    public function getProjectListOfUser($id)
    {
        return Project::whereHas('task', function ($query) use ($id) {
            $query->where('is_archived', 0)
                  ->whereHas('users', function ($innerQuery) use ($id) {
                      $innerQuery->where('user_id', $id);
                  });
        })
        ->orderBy('project_name', 'ASC')
        ->get();
    }
}
