<?php

namespace App\Http\Controllers;

use App\Exports\TaskSessionExport;
use App\Models\Branch;
use App\Models\Project;
use App\Models\ProjectCredentials;
use App\Models\ProjectDocuments;
use App\Models\QaIssue;
use App\Models\Task;
use App\Models\TaskApprover;
use App\Models\TaskAssignedUsers;
use App\Models\TaskChecklist;
use App\Models\TaskDocument;
use App\Models\TaskRejection;
use App\Models\TaskSession;
use App\Models\TaskStatusType;
use App\Models\TaskTag;
use App\Models\User;
use App\Services\TaskService as TService;
use App\Traits\GeneralTrait;
use Auth;
use Carbon\Carbon;
use Facades\App\Services\TaskReject;
use Facades\App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class TaskController extends Controller
{
    use GeneralTrait;

    public $pagination;
    protected $taskService;

    public function __construct(TService $taskService)
    {
        $this->pagination = config('general.pagination');
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->enhanceMemoryLimit();
        $isClient = $this->isClient();
        $currentUserId = $this->getCurrentUserId();
        $projectQuery = Project::orderBy('project_name', 'ASC');
        $clientProjects = [];

        if ($isClient) {
            $clientProjects = $this->taskService->getClientProjects($isClient, $currentUserId);
            $projectQuery = $projectQuery->whereIn('id', $clientProjects);
        }

        $users = $this->getUserNotClients();
        $admins = $this->getAdmins();
        $types = $this->getTaskStatusTypes();
        $projects = $projectQuery->get();
        $tasks = $this->taskService->getTasks($isClient, $clientProjects);
        $clientsList = $this->getClientsListByUserId($isClient, $currentUserId);
        $searchs = $this->taskService->getSearchs();
        $tags = $this->getTags();

        return view('tasks.index', compact('projects', 'users', 'clientsList', 'tasks', 'searchs', 'tags', 'admins', 'types'));
    }

    private function enhanceMemoryLimit()
    {
        $this->taskService->enhanceMemoryLimit();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->taskService->addNew($request);
        $res = [
            'status' => 'OK',
            'message' => 'Task created successfully'
        ];

        return response()->json($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subTask = $this->createSet($id);
        $list = $subTask == null ? '' : $this->setStyle($subTask);
        $users = User::notClients()->orderBy('first_name', 'ASC')->get();
        $admins = User::admins()->orderBy('first_name', 'ASC')->get();
        $userList = User::whereHas('users_task_session', function ($q) use ($id) {
            $q->where('task_id', $id);
        })->orderBy('first_name', 'ASC')->get();
        $task = Task::with('children', 'project', 'users', 'project.client', 'project.projectUsers', 'approvers')->where('tasks.id', '=', $id)->first();
        $parent = null;
        $child = null;
        if (! $task) {
            abort(404);
        }
        if (Gate::forUser(Auth::user())->denies('client-project-view', $task->project)) {
            abort(403);
        }

        if ($task->parent_id) {
            $parent = Task::select('id', 'title', 'priority', 'description')->where('id', $task->parent_id)->first();
        }
        $taskSession = TaskSession::where('task_id', $id)->with('user')
        ->orderByDesc('created_at')->paginate($this->pagination);

        $total = $this->getTotalSession($taskSession);
        $totalBilled = $this->getTotalBillableSession($taskSession);
        $taskCompletions = TaskRejection::with('users')->where('task_id', $id)->where('reason', '')->get();
        $taskUsers = [];
        foreach ($task->users as $taskUser) {
            $taskUsers[] = $taskUser->id;
        }
        $taskRejections = TaskRejection::query()->with('users')->where('task_id', $id)->when(auth()->user()->cannot('manage-tasks'), function ($query) use ($taskUsers) {
            return $query->whereIn('user_id', $taskUsers);
        })->where('reason', '!=', '')->orderBy('created_at', 'DESC')->get();

        $projectFiles = ProjectDocuments::where('project_id', $task->project_id)->get();

        $projectCredentials = ProjectCredentials::where('project_id', $task->project_id)->get();

        $branches = Branch::where('task_id', $task->id)->get();

        $allSubTasks = Task::where('parent_id', $id)->alltask()->orderBy('updated_at', 'DESC')->get();

        $upcomingSubTasks = Task::where('parent_id', $id)->upcomingtask()->orderBy('updated_at', 'DESC')->get();
        $ongoingSubTasks = Task::where('parent_id', $id)->ongoingtask()->orderBy('updated_at', 'DESC')->get();
        $completedSubTasks = Task::where('parent_id', $id)->completedtask()->orderBy('updated_at', 'DESC')->get();
        $totalSubTasks = count($allSubTasks) + count($upcomingSubTasks) + count($completedSubTasks);
        $tags = TaskTag::select('title', 'slug')->orderBy('title')->get();

        $qaIssues = QaIssue::orderBy('title', 'ASC')->get();

        $exceed_reasons = TaskAssignedUsers::with('user')->where('task_id', $id)->get();

        $types = TaskStatusType::orderBy('order', 'ASC')->get();

        $showActualEstimateToUser = $this->taskService->canShowActualEstimateToUser();

        $parentTasks = Task::where([
            'project_id' => $task->project_id,
            'parent_id' => null,
            ['id', '<>', $task->id]
        ])->notArchived()->orderBy('title', 'Asc')->get();

        return view('tasks.viewDetails', compact('task', 'parentTasks', 'taskSession', 'users', 'parent', 'subTask', 'list', 'projectFiles', 'projectCredentials', 'branches', 'userList', 'total', 'totalBilled', 'taskRejections', 'taskCompletions', 'allSubTasks', 'upcomingSubTasks', 'ongoingSubTasks', 'completedSubTasks', 'tags', 'admins', 'qaIssues', 'exceed_reasons', 'types', 'totalSubTasks', 'showActualEstimateToUser'));
    }

    public function getTotalSession($taskSession)
    {
        $total = 0;

        foreach ($taskSession as $item) {
            $total += $item->total;
        }

        return $total;
    }

    public function getTotalBillableSession($taskSession)
    {
        $total = 0;

        foreach ($taskSession as $item) {
            $total += $item->billed_today;
        }

        return $total;
    }

    public function getUserSession()
    {
        $userSession = request('userId');
        $sessionType = request('type');
        $daterange = request('daterange');
        $id = request('taskId');

        $userList = User::whereHas('users_task_session', function ($q) use ($id) {
            $q->where('task_id', $id);
        })->orderBy('first_name', 'ASC')->get();

        $taskSession = TaskSession::with('user')->where('task_id', $id);

        $taskSession->when(! empty($daterange), function ($q) use ($daterange) {
            $split = explode(' - ', $daterange);
            $startDate = Carbon::createFromFormat('M d, Y', $split[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('M d, Y', $split[1])->format('Y-m-d');

            return $q->whereBetween('created_at', [$startDate, $endDate]);
        });

        if ($userSession != '') {
            $taskSession = $taskSession->where('user_id', $userSession);
        }

        if ($sessionType != '') {
            $taskSession = $taskSession->where('session_type', $sessionType);
        }

        $taskSession = $taskSession->orderByDesc('created_at')->paginate($this->pagination);

        $total = $this->getTotalSession($taskSession);

        $task = Task::where('id', $id)->first();

        $content = view('tasks.main-task-session', compact('task', 'userList', 'taskSession', 'total'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * This PHP function exports a timesheet in Excel format based on user session, type, task ID, and
     * date range.
     *
     * @param Request request  is an instance of the Request class which contains the data sent
     * by the client in the HTTP request.
     * @return an Excel file download response
     */
    public function exportSession(Request $request)
    {
        $request['userId'] = $request->userSession;
        $request['type'] = $request->userSessionType;
        $request['taskId'] = $request->taskId;
        $request['daterange'] = $request->daterange;
        if (auth()->user()->can('manage-tasks')) {
            $response = Excel::download(new TaskSessionExport($request), 'Tasksession.xlsx');
            ob_end_clean();

            return $response;
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $isClient = Auth::user()->hasRole('client');
        $currentUserId = auth()->user()->id;

        if ($isClient) {
            $projects = Project::whereHas('client', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            })->orderBy('project_name')->get();
        } else {
            $projects = Project::orderBy('project_name')->get();
        }

        $task = Task::with('documents')->with('approvers')->where('id', $id)->first();
        if (Gate::forUser(Auth::user())->denies('client-project-view', $task->project)) {
            abort(403);
        }
        $users = User::active()->notClients()->select('id', 'first_name', 'last_name')->orderBy('first_name', 'ASC')->get();

        $assignedUsers = TaskAssignedUsers::where('task_id', $id)->get();

        $taskUsers = [];
        foreach ($assignedUsers as $taskUser) {
            $taskUsers[] = $taskUser->user_id;
        }

        $admins = User::admins()->orderBy('first_name', 'ASC')->get();

        $tags = TaskTag::select('title', 'slug')->orderBy('title')->get();

        $types = TaskStatusType::orderBy('order', 'ASC')->get();

        $parentTasks = Task::where([
            'project_id' => $task->project_id,
            'parent_id' => null,
            ['id', '<>', $task->id]
        ])->notArchived()->orderBy('title', 'Asc')->get();

        return view('tasks.edit', compact('task', 'parentTasks', 'users', 'taskUsers', 'projects', 'tags', 'admins', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        TaskService::editTask($request, $id);

        $res = [
            'status' => 'OK',
            'message' => 'Task Updated successfully'
        ];

        return response()->json($res);
    }

    public function updateDetails()
    {
        $id = request('task_id');
        $subTask = $this->createSet($id);
        $list = $subTask == null ? '' : $this->setStyle($subTask);
        $users = User::all();
        $userList = User::whereHas('users_task_session', function ($q) use ($id) {
            $q->where('task_id', $id);
        })->orderBy('first_name', 'ASC')->get();
        $task = Task::with('project', 'users', 'project.client', 'project.projectUsers', 'approvers')->where('tasks.id', '=', $id)->first();
        $parent = null;
        $child = null;
        if ($task->parent_id) {
            $parent = Task::select('id', 'title', 'priority')->where('id', $task->parent_id)->first();
        }

        $taskSession = TaskSession::where('task_id', $id)->with('user')
        ->orderByDesc('created_at')->paginate($this->pagination);
        $totalBilled = $this->getTotalBillableSession($taskSession);

        $total = $this->getTotalSession($taskSession);

        $projectFiles = ProjectDocuments::where('project_id', $task->project_id)->get();

        $projectCredentials = ProjectCredentials::where('project_id', $task->project_id)->get();

        $branches = Branch::where('task_id', $task->id)->get();

        $taskCompletions = TaskRejection::with('users')->where('task_id', $id)->where('reason', '')->get();

        $taskRejections = TaskRejection::with('users')->where('task_id', $id)->where('reason', '!=', '')->orderBy('created_at', 'DESC')->get();

        $estimatedTime = $task->project->task->sum('estimated_time');
        $actualEstimatedTime = $task->project->task->sum('actual_estimated_time');
        $timeSpent = $task->project->task->sum('time_spent');

        $allSubTasks = Task::where('parent_id', $id)->alltask()->orderBy('updated_at', 'DESC')->get();
        $upcomingSubTasks = Task::where('parent_id', $id)->upcomingtask()->orderBy('updated_at', 'DESC')->get();
        $ongoingSubTasks = Task::where('parent_id', $id)->ongoingtask()->orderBy('updated_at', 'DESC')->get();
        $completedSubTasks = Task::where('parent_id', $id)->completedtask()->orderBy('updated_at', 'DESC')->get();
        $totalSubTasks = count($allSubTasks) + count($upcomingSubTasks) + count($completedSubTasks);
        $tags = TaskTag::select('title', 'slug')->orderBy('title')->get();
        $admins = User::admins()->orderBy('first_name', 'ASC')->get();

        $qaIssues = QaIssue::orderBy('title', 'ASC')->get();

        $exceed_reasons = TaskAssignedUsers::with('user')->where('task_id', $id)->get();

        $types = TaskStatusType::orderBy('order', 'ASC')->get();

        $showActualEstimateToUser = $this->taskService->canShowActualEstimateToUser();

        $parentTasks = Task::where([
            'project_id' => $task->project_id,
            'parent_id' => null,
            ['id', '<>', $task->id]
        ])->notArchived()->orderBy('title', 'Asc')->get();

        $content = view('tasks.show', compact('task', 'parentTasks', 'taskSession', 'totalBilled', 'users', 'parent', 'subTask', 'list', 'projectFiles', 'taskRejections', 'taskCompletions', 'userList', 'total', 'projectCredentials', 'branches', 'estimatedTime', 'actualEstimatedTime', 'timeSpent', 'allSubTasks', 'upcomingSubTasks', 'ongoingSubTasks', 'completedSubTasks', 'tags', 'admins', 'qaIssues', 'exceed_reasons', 'types', 'totalSubTasks', 'showActualEstimateToUser'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TaskService::deleteTask($id);

        $res = [
            'status' => 'OK',
            'message' => 'Task archived successfully'
        ];

        return response()->json($res);
    }

    /**
     *Search blade return method.
     */
    public function taskSearch(Request $request)
    {
        $searchedTask = [];
        $searchedProject = [];
        $isClient = $this->isClient();
        $currentUserId = $this->getCurrentUserId();
        $clientProjects = $this->taskService->getClientProjects($isClient, $currentUserId);
        $tasks = $this->taskService->getTasksForTaskSearch($request, $clientProjects);
        $searchedTask = $this->taskService->getSearchedTask($request);
        $searchedProject = $this->taskService->getSearchedProjects($request);
        $users = $this->getUsers();
        $clientsList = $this->getClientsListByUserId($isClient, $currentUserId);
        $projects = $this->taskService->getProjectsForTaskSearch($isClient, $clientProjects);
        $tags = $this->getTags();
        $admins = $this->getAdmins();
        $types = $this->getTaskStatusTypes();

        return view('tasks.index', compact('projects', 'users', 'clientsList', 'tasks', 'searchedTask', 'tags', 'searchedProject', 'admins', 'types'));
    }

    /** Get the tasks of a user */
    public function getTasksProjectUser()
    {
        $tasks = Task::select('id', 'title', 'start_date')->where('project_id', request()->projectId)->whereHas('users', function ($q) {
            $q->where('user_id', '=', Auth::user()->id);
        })->get();

        return response()->json(['status' => 'OK', 'data' => $tasks]);
    }

    /**Ajax function to create a task */
    public function createTaskAjax(Request $request)
    {
        TaskService::addNew($request);

        $content = $this->taskList(request('project_id'));

        $res = [
            'status' => 'OK',
            'message' => 'Task Created successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**Ajax function to update a task */
    public function updateTaskAjax(Request $request)
    {
        TaskService::editTask($request, request('task_id'));

        $content = $this->taskList(request('project_id'));

        $res = [
            'status' => 'OK',
            'message' => 'Task Updated successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**Ajax function to delete a task */
    public function deleteTaskAjax(Request $request)
    {
        TaskService::deleteTask(request('taskId'));

        $content = $this->taskList(request('projectId'));

        $res = [
            'status' => 'OK',
            'message' => 'Task archived successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /** Get the list of ongoing, upcoming and completed tasks */
    public function taskList($id)
    {
        $allTasks = Task::alltask($id)->parents()->where('is_archived', 0)->orderBy('updated_at', 'DESC')->get();
        $upcomingTasks = Task::upcomingtask($id)->parents()->where('is_archived', 0)->orderBy('updated_at', 'DESC')->get();
        $ongoingTasks = Task::ongoingtask($id)->parents()->where('is_archived', 0)->orderBy('updated_at', 'DESC')->get();
        $completedTasks = Task::completedtask($id)->parents()->where('is_archived', 0)->orderBy('updated_at', 'DESC')->get();
        $archivedTasks = Task::archivedTask($id)->parents()->where('is_archived', 1)->orderBy('updated_at', 'DESC')->get();
        $users = User::notClients()->orderBy('first_name', 'ASC')->get();
        $content = view('projects.task_list', compact('allTasks', 'upcomingTasks', 'ongoingTasks', 'completedTasks', 'archivedTasks', 'users'))->render();

        return $content;
    }

    /**Ajax function to update task progress */
    public function updateProgress()
    {
        $id = request('taskId');
        $taskData = [
            'percent_complete' => request('progress'),
        ];

        Task::find($id)->update($taskData);

        $res = [
            'status' => 'OK',
            'message' => 'Task Progress Updated successfully',
        ];

        return response()->json($res);
    }

    public function createSubTask(Request $request)
    {
        $taskId = TaskService::addNew($request);

        Task::find($taskId)->update(['parent_id' => request('task_parent')]);

        $subTask = $this->createSet(request('task_parent'));

        $task = Task::find(request('task_parent'));
        //$content = view('tasks.sub-task.sub-task-list',compact('task'))->render();

        $res = [
            'status' => 'OK',
            'message' => 'Sub Task Created successfully',
            'data' => '',
        ];

        return response()->json($res);
    }

    public function createSet($id)
    {
        $data = [];
        $list = Task::select('id', 'title', 'description', 'end_date', 'priority')->where('parent_id', $id)->get();
        if (count($list) == 0) {
            return null;
        } else {
            foreach ($list as $task) {
                array_push($data, ['id' => $task->id, 'title' => $task->title, 'description' => $task->description, 'deadline' => $task->end_date_format, 'priority' => $task->priority, 'list' => $this->createSet($task->id)]);
            }
        }

        return $data;
    }

    public function setStyle($subTask)
    {
        $page = '';
        foreach ($subTask as $item) {
            $page .= '<ol class="dd-list">
                <a href="../tasks/'.$item['id'].'">
                <li class="dd-item">
                    <div class="payment-card alert-danger">';

            if ($item['priority'] == 'Low') {
                $page .= '<label class="label label-success">Low</label>';
            }
            if ($item['priority'] == 'Medium') {
                $page .= '<label class="label label-primary">Medium</label>';
            }
            if ($item['priority'] == 'High') {
                $page .= '<label class="label label-danger">High</label>';
            }

            $page .= '<strong> '.$item['title'].'</strong><span class="pull-right">Deadline : '.$item['deadline'].'</span>';
            if ($item['description']) {
                $page .= '<div class="" style="color:#676a6c; margin-top:10px;">'.(strlen($item['description']) > 200 ? substr($item['description'], 0, 200).'...' : $item['description']).'</div>';
            }
            $page .= '</div></li></a>';
            if ($item['list'] != null) {
                $page .= $this->setStyle($item['list']);
            }
            $page .= '</ol>';
        }

        return $page;
    }

    public function checkSubTasks()
    {
        if (request('status') != 'Done') {
            return response()->json(['status' => false]);
        }
        // Check if there are any subtasks
        $hasSubTasks = Task::where('parent_id', request('task_id'))->where('status', '!=', 'Done')->exists();

        return response()->json(['status' => $hasSubTasks]);
    }

    public function changeStatus()
    {
        if (request('status') == 'Done') {
            $taskSession = TaskSession::select('id')->where('task_id', request('task_id'));
            if ($taskSession->whereIn('current_status', ['started', 'pause', 'resume'])->count() > 0) {
                return response()->json(['flag' => false, 'message' => 'Cannot change the status to "Done" as someone is already working on it !']);
            }
            Task::find(request('task_id'))->update(['status' => request('status'), 'percent_complete' => 100]);
            TaskRejection::where('task_id', request('task_id'))->where('reason', '')->delete();

            $subTasks = Task::where('parent_id', request('task_id'))->where('status', '!=', 'Done');
            if ($subTasks->count() > 0 && request('updateStatus') == 1) {
                $subTasks = $subTasks->get();
                foreach ($subTasks as $subTask) {
                    $taskSession = TaskSession::select('id')->where('task_id', $subTask->id);
                    if ($taskSession->whereIn('current_status', ['started', 'pause', 'resume'])->count() <= 0) {
                        Task::find($subTask->id)->update(['status' => request('status'), 'percent_complete' => 100]);
                        TaskRejection::where('task_id', $subTask->id)->where('reason', '')->delete();
                    }
                }
            }
        } elseif (request('status') == 'Backlog') {
            Task::find(request('task_id'))->update(['status' => request('status'), 'percent_complete' => 0]);
        } elseif (request('status') == 'In Progress') {
            $task = Task::find(request('task_id'));
            if ($task->parent_id) {
                Task::find($task->parent_id)->update(['status' => 'In Progress']);
            }
            Task::find(request('task_id'))->update(['status' => request('status'), 'percent_complete' => 20]);
        } elseif (request('status') == 'Development Complete') {
            Task::find(request('task_id'))->update(['status' => request('status'), 'percent_complete' => 50]);
        } else {
            Task::find(request('task_id'))->update(['status' => request('status')]);
        }

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function changeStatusFinish()
    {
        if (request('status') != 'Done') {
            Task::find(request('task_id'))->update(['status' => request('status'), 'percent_complete' => 50]);
            $task = TaskRejection::where('task_id', request('task_id'))->where('user_id', Auth::user()->id)->where('reason', '')->first();
            if ($task) {
                return response()->json(['message' => 'Since you have completed the task, check the rejection list for updates.']);
            }

            $data = [
                'severity' => '',
                'reason' => '',
                'task_id' => request('task_id'),
                'user_id' => Auth::user()->id,
            ];

            $task = Task::with('approvers')->where('id', request('task_id'))->first();
            if (! $task->users->contains('id', auth()->user()->id) && auth()->user()->can('manage-tasks')) {
                $flag = 0;
                foreach ($task->users as $user) {
                    if ($user->designation->name != 'Quality Analyst') {
                        $data['user_id'] = $user->id;
                        $taskRejectionEntry = TaskRejection::where('task_id', request('task_id'))->where('user_id', $user->id)->where('reason', '')->first();
                        if (! $taskRejectionEntry) {
                            $flag = 1;
                            TaskRejection::create($data);
                        }
                    }
                }
                if ($flag == 1) {
                    return response()->json(['message' => 'status updated successfully']);
                }
            }

            $task = TaskRejection::where('task_id', request('task_id'))->where('user_id', Auth::user()->id)->where('reason', '')->first();
            if ($task) {
                return response()->json(['message' => 'Since you have completed the task, check the rejection list for updates.']);
            }

            TaskRejection::create($data);
        } else {
            Task::find(request('task_id'))->update(['status' => request('status'), 'percent_complete' => 100]);
            TaskRejection::where('task_id', request('task_id'))->where('reason', '')->delete();
        }
        TaskService::sendTaskCompleteNotification(Task::find(request('task_id')));

        return response()->json(['message' => 'Since you have completed the task, check the rejection list for updates.']);
    }

    public static function returnOverdueTask()
    {
        return Task::with('users', 'project')->where(function ($query) {
            $query->whereRaw('estimated_time < time_spent')->orWhere('end_date', '<', date('Y-m-d'));
        })->whereIn('status', config('overdue-status'))->orderBy('updated_at', 'DESC')->get();
    }

    public function acceptCompletion()
    {
        $taskRejectionData = TaskRejection::find(request('id'));
        $id = $taskRejectionData->task_id;
        $taskRejectionData->delete();
        if (TaskRejection::where('task_id', $id)->where('reason', '')->count() == 0) {
            $task = Task::find($id);
            $task->update(['status' => request('status'), 'percent_complete' => 100]);
            TaskService::sendTaskCompleteNotification($task);
        }

        return response()->json(['message' => 'Accepted. Please check and updated the status.']);
    }

    public function rejectCompletion()
    {
        TaskReject::rejectTaskCompletion(request('task_id'));

        Task::find(request('task_id'))->update(['status' => request('status'), 'percent_complete' => 20]);

        return response()->json(['message' => 'Rejected and updated the task progress to In Progress.']);
    }

    public function updateChecklist(Request $request)
    {
        $data = [];
        if ($request->type == 'developer') {
            $data['developer_status'] = $status = 1 - $request->status;
        } else {
            $data['reviewer_status'] = $status = 1 - $request->status;
        }

        $taskChecklist = TaskChecklist::find($request->id);
        $taskChecklist->update($data);

        return response()->json(['message' => 'Checklist mark as completed.', 'status' => $status]);
    }

    public function storeTag(Request $request)
    {
        $request->validate([
            'title' => 'required | unique:task_tags,title',
        ]);

        $input = $request->except('_token');
        $input['slug'] = $input['title'];

        $tag = TaskTag::create($input);

        $res = [
            'status' => 'OK',
            'message' => 'Tag Added successfully',
            'tag' => $tag
        ];

        return response()->json($res);
    }

    public function deleteDoc($id)
    {
        TaskDocument::find($id)->delete();

        $res = [
            'status' => 'OK',
            'message' => 'Document Deleted successfully',
        ];

        return response()->json($res);
    }

    public function viewComments($id)
    {
        $task = $this->findTask($id);

        $content = view('tasks.comments', compact('task'))->render();
        $res = [
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function viewCommentsTasks()
    {
        $task = $this->findTask(request('task_id'));

        $content = view('tasks.comments', compact('task'))->render();
        $res = [
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Autocomplete data for task search.
     */
    public function getAutocompleteDataTask(Request $request)
    {
        $currentUserId = $this->getCurrentUserId();
        $isClient = $this->isClient();
        $tasks = $this->taskService->getTasksForAutocompleteData($request, $currentUserId, $isClient);

        return response()->json($tasks);
    }

    /**
     * Autocomplete data for project search.
     */
    public function getAutocompleteDataProject(Request $request)
    {
        $currentUserId = $this->getCurrentUserId();
        $isClient = $this->isClient();
        $projects = $this->taskService->getProjectsForAutocompleteData($request, $currentUserId, $isClient);

        return response()->json($projects);
    }

    public function adminApproveTask(Request $request)
    {
        $taskId = $request->taskId;

        $taskApproval = TaskApprover::whereTaskId($taskId)->whereUserId(Auth::user()->id)->first();

        if ($taskApproval) {
            $taskApproval->update(['status' => 1]);
        }

        return response()->json(['message' => 'Task approved successfully']);
    }

    public function getSubTaskList()
    {
        $id = request('task_id');
        $allSubTasks = Task::where('parent_id', $id)->alltask()->orderBy('updated_at', 'DESC')->get();
        $upcomingSubTasks = Task::where('parent_id', $id)->upcomingtask()->orderBy('updated_at', 'DESC')->get();
        $ongoingSubTasks = Task::where('parent_id', $id)->ongoingtask()->orderBy('updated_at', 'DESC')->get();
        $completedSubTasks = Task::where('parent_id', $id)->completedtask()->orderBy('updated_at', 'DESC')->get();

        $content = view('tasks.sub-task.sub-task-list', compact('allSubTasks', 'upcomingSubTasks', 'ongoingSubTasks', 'completedSubTasks'))->render();

        $res = [
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function getAssigneesList()
    {
        $id = request('task_id');

        $task = Task::with('users')->where('id', $id)->first();

        $content = view('tasks.assignees', compact('task'))->render();

        $res = [
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function updateTaskStatus()
    {
        $id = request('task_id');

        $task = Task::with('project', 'users', 'project.client', 'project.projectUsers', 'approvers')->where('tasks.id', '=', $id)->first();

        $content = view('tasks.task-progress-status', compact('task'))->render();

        $res = [
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function checkWhetherExceedsTime()
    {
        $id = request('task_id');

        $task = Task::select('estimated_time', 'time_spent')->where('id', '=', $id)->first();

        $flag = false;
        if (! Auth::user()->hasrole('client')) {
            $temp = TaskAssignedUsers::where('user_id', Auth::user()->id)->where('task_id', $id)->first();
            if ($temp) {
                $diff = $task->time_spent - $task->estimated_time;

                if ($diff > ((10 * $task->estimated_time) / 100) && $task->time_spent > 3) {
                    $flag = true;
                }
            }
        }

        return response()->json(['flag' => $flag]);
    }

    public function addTimeExceedReason(Request $request)
    {
        $request->validate([
            'reason' => 'required',
        ]);

        $temp = TaskAssignedUsers::select('exceed_reason')->where('user_id', Auth::user()->id)->where('task_id', $request->task_id)->first();

        TaskAssignedUsers::where('user_id', Auth::user()->id)->where('task_id', $request->task_id)->update(['exceed_reason' => $temp->exceed_reason.'</br>'.$request->reason]);

        return response()->json(['flag' => 'Added']);
    }

    public function checkWhetherExceedsTimeWithReason()
    {
        $id = request('id');

        $row = TaskRejection::where('id', $id)->first();

        $task = Task::select('estimated_time', 'time_spent')->where('id', $row->task_id)->first();

        $assignDetails = TaskAssignedUsers::where('task_id', $row->task_id)->where('user_id', $row->user_id)->first();

        if ($assignDetails && $assignDetails->exceed_reason) {
            return response()->json(['flag' => true, 'exceed_reason' => $assignDetails->exceed_reason]);
        }

        return response()->json(['flag' => false]);
    }

    public function checkExceedTime()
    {
        $flag = false;

        if (! Auth::user()->hasrole('client')) {
            $id = request('task_id');

            $task = Task::select('estimated_time', 'time_spent')->where('id', '=', $id)->first();

            $diff = $task->time_spent - $task->estimated_time;

            if ($diff > ((10 * $task->estimated_time) / 100) && $task->time_spent > 3) {
                $flag = true;
            }
        } else {
            $flag = false;
        }

        return response()->json(['flag' => $flag]);
    }

    public function viewAgile($id)
    {
        $project = $this->findProject($id);
        $parentTasks = $this->taskService->getParentTasksHasChildren($id);
        $status_types = $this->getTaskStatusTypes();
        $data = [];
        $data = $this->taskService->getDataForViewAgile($status_types, $data, $id);
        $users = $this->taskService->getUserNotClients();
        $admins = $this->getAdmins();
        $tags = $this->getTags();

        return view('projects.agile.index', compact('project', 'data', 'parentTasks', 'users', 'admins', 'tags'));
    }

    public function searchAgile($id)
    {
        if (empty(request('task_id'))) {
            return redirect('/agile-board/'.request('project_id'));
        }

        $parentTasks = $this->taskService->getParentTasksHasChildren(request('project_id'));
        $project = $this->findProject(request('project_id'));
        $status_types = $this->taskService->getTaskStatusTypes();
        $users = $this->taskService->getUserNotClients();
        $admins = $this->getAdmins();
        $tags = $this->getTags();
        $data = [];
        $data = $this->taskService->getDataForSearchAgile($status_types, $data);

        return view('projects.agile.index', compact('project', 'data', 'parentTasks', 'users', 'admins', 'tags'));
    }

    public function updateOrder()
    {
        $this->taskService->updateOrder();

        return response()->json(['Success' => 'OK']);
    }

    public function uploadTaskFiles()
    {
        $file = request('file');
        TaskDocument::create([
            'task_id' => request('task_id'),
            'path' => $file->storeAs('tasks/documents', $file->getClientOriginalName())
        ]);
    }

    public function getDocuments()
    {
        $task = Task::with('children', 'project', 'users', 'project.client', 'project.projectUsers', 'approvers')->where('tasks.id', '=', request('task_id'))->first();

        $content = view('tasks.task-documents', compact('task'))->render();

        $res = [
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function archivedTasks()
    {
        $isClient = $this->isClient();
        $currentUserId = $this->getCurrentUserId();
        $clientProjects = [];

        $projectQuery = Project::orderBy('project_name', 'ASC');

        if ($isClient) {
            $clientProjects = $this->taskService->getClientProjects($isClient, $currentUserId);
            $projectQuery = $projectQuery->whereIn('id', $clientProjects);
        }
        $projects = $projectQuery->get();

        $users = $this->getUserNotClients();
        $admins = $this->getAdmins();
        $types = $this->getTaskStatusTypes();
        $tasks = $this->taskService->getArchivedTasks($clientProjects);
        $clientsList = $this->getClientsListByUserId(! empty($clientProjects), $currentUserId);
        $searchs = $this->taskService->getSearchs();
        $tags = $this->getTags();

        return view('tasks.archived.index', compact('projects', 'users', 'clientsList', 'tasks', 'searchs', 'tags', 'admins', 'types'));
    }

    public function archivedTaskSearch(Request $request)
    {
        $isClient = $this->isClient();
        $currentUserId = $this->getCurrentUserId();
        $clientProjects = $this->taskService->getClientProjects($isClient, $currentUserId);
        $tasks = $this->taskService->getTasksForArchivedTasksSearch($request);
        $searchedTask = $this->taskService->getSearchedTask($request);
        $searchedProject = $this->taskService->getSearchedProject($request);
        $users = $this->getUsers();
        $clientsList = $this->getClientsListByUserId(! empty($clientProjects), $currentUserId);
        $projects = $this->taskService->getProjects($clientProjects);
        $tags = $this->getTags();
        $admins = $this->getAdmins();
        $types = $this->getTaskStatusTypes();

        return view('tasks.archived.index', compact('projects', 'users', 'clientsList', 'tasks', 'searchedTask', 'tags', 'searchedProject', 'admins', 'types'));
    }

    public function changeArchive()
    {
        $this->taskService->changeArchive();
        $res = [
            'success' => 'OK',
        ];

        return response()->json($res);
    }

    public function getProjectTasks($projectId)
    {
        $allTasks = $this->taskService->getAllTasks($projectId);
        $res = [
            'status' => 'success',
            'data' => $allTasks,
        ];

        return response()->json($res);
    }

    /**Ajax function to destroy a task */
    public function destroyTaskAjax(Request $request)
    {
        $res = $this->checkTaskStatus(request('taskId'));
        $content = $this->taskList(request('projectId'));
        $res['data'] = $content;

        return response()->json($res);
    }

    public function checkTaskStatus($id)
    {
        $task = Task::find($id);
        if (! in_array($task->status, ['Backlog'])) {
            return [
                'status' => 'error',
                'message' => 'Cannot delete tasks which are in progress or completed.'
            ];
        }
        if (! $task->parent_id && Task::where('parent_id', $id)->whereNotIn('status', ['Backlog'])->exists()) {
            return [
                'status' => 'error',
                'message' => 'You cannot delete a task if it has subtasks that are currently in progress or completed.'
            ];
        }

        TaskService::destroyTask(request('taskId'));

        return [
            'status' => 'success',
            'message' => 'Task Deleted successfully'
        ];
    }
}
