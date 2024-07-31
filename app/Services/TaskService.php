<?php

namespace App\Services;

use App\Events\TaskCreatorNotify;
use App\Events\TaskDeleteNotify;
use App\Events\TaskEditNotify;
use App\Events\TaskStatusChangeNotify;
use App\Events\UserTaskNotify;
use App\Models\Project;
use App\Models\ProjectAssignedUsers;
use App\Models\Settings;
use App\Models\Task;
use App\Models\TaskAssignedUsers;
use App\Models\TaskAssignedUsersHour;
use App\Models\TaskDocument;
use App\Models\TaskSession;
use App\Models\User;
use App\Notifications\TaskStatusNotification;
use App\Repository\TaskRepository;
use App\Rules\ValidateTask;
use App\Traits\GeneralTrait;
use Auth;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Laravelista\Comments\Comment;
use Notification;

date_default_timezone_set('Asia/Kolkata');

class TaskService
{
    use GeneralTrait;

    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function addNew(Request $request)
    {
        $assignedUsers = [];
        $files = [];
        if (Auth::user()->hasRole('client')) {
            $request->validate([
                'project_id' => 'required',
                'task_title' => ['required', new ValidateTask(request('project_id'), $taskid = null, request('task_id'))],
                'task_url' => 'nullable|url'
            ]);
        } else {
            $request->validate([
                'project_id' => 'required',
                'task_title' => ['required', new ValidateTask(request('project_id'), $taskid = null, request('task_id'))],
                'estimated_time' => 'required|regex:/^\d*(\.\d{1,2})?$/',
                'actual_estimated_time' => 'required|regex:/^\d*(\.\d{1,2})?$/',
                'start_date' => 'required|date_format:d/m/Y',
                'end_date' => 'required|date_format:d/m/Y',
                'task_url' => 'nullable|url'
            ]);
        }
        $project = Project::select('project_id')->where('id', request('project_id'))->first();

        $taskData = [
            'project_id' => request('project_id'),
            'title' => request('task_title'),
            'priority' => request('task_priority'),
            'estimated_time' => request('estimated_time') ?? 0,
            'actual_estimated_time' => request('actual_estimated_time') ?? 0,
            'description' => request('task_description'),
            'percent_complete' => 0,
            'time_spent' => 0,
            'task_url' => request('task_url'),
            'task_id' => request('task_id'),
            'start_date' => request('start_date') != '' ? Carbon::createFromFormat('d/m/Y', request('start_date'))->format('Y-m-d') : null,
            'end_date' => request('end_date') != '' ? Carbon::createFromFormat('d/m/Y', request('end_date'))->format('Y-m-d') : null,
            'parent_id' => request('task_parent') ?: null,
            'reviewer_id' => request('reviewer_id') ?: null,
            'status' => 'Backlog',
            'tag' => request('tag'),
            'created_by' => Auth::user()->id,
            'is_archived' => 0
        ];

        if ($request->has('add_to_board')) {
            $taskData += ['add_to_board' => 1];
        } else {
            $taskData += ['add_to_board' => 0];
        }

        $task = Task::with('project')->where('project_id', request('project_id'))->orderBy('id', 'DESC')->first();

        if ($task) {
            $last_id = explode('-', $task->code);
            $taskData += ['code' => $task->project->project_id.'-'.((int) end($last_id) + 1)];
        } else {
            $project = Project::where('id', request('project_id'))->first();
            $taskData += ['code' => $project->project_id.'-1'];
        }

        $newTask = Task::create($taskData);
        $this->convertDescriptionToComments($newTask);
        $taskId = $newTask->id;

        // if ($task) {
        //     $last_id = explode('-', $task->code);
        //     Task::find($taskId)->update(['code' => $task->project->project_id . "-" . ((int) end($last_id) + 1)]);
        // } else {
        //     $project = Project::where('id', request('project_id'))->first();
        //     Task::find($taskId)->update(['code' => $project->project_id . "-1"]);
        // }

        $assignedUsers[] = $newTask->reviewer_id;
        $assignedUsers = array_filter($assignedUsers);
        if (! empty(request('task_assigned_users'))) {
            $assignedUsers = array_unique(array_merge($assignedUsers, request('task_assigned_users')));
        }

        event(new UserTaskNotify($newTask, $newTask->fresh()->users));

        if (count($assignedUsers) > 0) {
            $newTask->users()->sync($assignedUsers);
            $newTask->save();
            event(new UserTaskNotify($newTask, $newTask->fresh()->users));
        }

        if (! empty(request('checklists'))) {
            $newTask->checklists()->sync(request('checklists'));
        }

        if (! empty(request('approval_users'))) {
            $newTask->approvers()->sync(request('approval_users'));
            event(new UserTaskNotify($newTask->fresh(), $newTask->fresh()->approvers));
        }

        $notificationUsers = $this->getTaskNotificationUsers(request('project_id'));
        if (! $notificationUsers->contains('id', auth()->user()->id)) {
            // If not, add auth::user() to the collection
            $notificationUsers->push(auth::user());
        }

        if (! empty($notificationUsers)) {
            event(new TaskCreatorNotify($notificationUsers, $newTask->fresh()));
        }

        if ($request->hasFile('files')) {
            $files = $this->multipleUpload(request('files'));
        }
        $taskAssignUsers = request('task_assigned_users');
        if (! empty($taskAssignUsers) && count($taskAssignUsers) > 0) {
            foreach ($taskAssignUsers as $taskAssignUser) {
                $projectUser = ProjectAssignedUsers::firstOrCreate([
                    'project_id' => request('project_id'),
                    'user_id' => $taskAssignUser
                ]);
            }
            if (count($taskAssignUsers) == 1) {
                $this->addUserDayHour($request, $taskId);
            }
        }

        foreach ($files as $file) {
            TaskDocument::create([
                'task_id' => $taskId,
                'path' => $file
            ]);
        }

        return $taskId;
    }

    /**
     * Get all assigned PM's for a project, if none get all PM's.
     *
     * @param  mixed  $projectId
     * @return array
     */
    public function getTaskNotificationUsers($projectId)
    {
        $role = config('general.email.creator-notification-user-role');
        $usersWithRole = ProjectAssignedUsers::active()->where('project_id', $projectId)
            ->whereHas('user.role', function ($query) use ($role) {
                $query->where('name', $role);
            })
            ->get()
            ->pluck('user');
        if ($usersWithRole->isEmpty()) {
            $usersWithRole = User::active()->whereHas('role', function ($query) use ($role) {
                $query->where('name', $role);
            })
            ->get();
        }

        return $usersWithRole;
    }

    public function editTask($request, $id)
    {
        $assignedUsers = [];
        $files = [];
        $approvers = [];
        if (Auth::user()->hasRole('client')) {
            $request->validate([
                'edit_task_title' => ['required', new ValidateTask(request('project_id'), $id, request('edit_task_id'))],
            ]);
        } else {
            $request->validate([
                'edit_task_title' => ['required', new ValidateTask(request('project_id'), $id, request('edit_task_id'))],
                'edit_task_start_date' => 'required|date_format:d/m/Y',
                'edit_task_end_date' => 'required|date_format:d/m/Y',
                'edit_estimated_time' => 'required|regex:/^\d*(\.\d{1,2})?$/',
                'edit_actual_estimated_time' => 'required|regex:/^\d*(\.\d{1,2})?$/',
                'edit_task_url' => 'nullable|url'
            ]);
        }
        $taskData = [];
        if (! Auth::user()->hasRole('client')) {
            $taskData = [
                'priority' => request('edit_task_priority'),
                'estimated_time' => request('edit_estimated_time'),
                'actual_estimated_time' => request('edit_actual_estimated_time'),
                'notes' => request('notes'),
                'percent_complete' => request('edit_percent_complete'),
                'start_date' => Carbon::createFromFormat('d/m/Y', request('edit_task_start_date'))->format('Y-m-d'),
                'end_date' => Carbon::createFromFormat('d/m/Y', request('edit_task_end_date'))->format('Y-m-d'),
                'reviewer_id' => request('reviewer_id') ?: null,

            ];
        }
        $taskData += [
            'title' => request('edit_task_title'),
            'description' => request('edit_task_description'),
            'task_url' => request('edit_task_url'),
            'task_id' => request('edit_task_id'),
            'tag' => request('tag'),
            'parent_id' => request('task_parent') ?: null,
        ];

        if ($request->has('add_to_board')) {
            $taskData += ['add_to_board' => 1];
        } else {
            $taskData += ['add_to_board' => 0];
        }

        if ($request->has('is_archived')) {
            $taskData += ['is_archived' => 1];
        } else {
            $taskData += ['is_archived' => 0];
        }
        if (! Auth::user()->hasRole('client')) {
            if (request('status') == 'Done') {
                $taskData += ['percent_complete' => 100, 'status' => request('status')];
            } elseif (request('status') == 'Backlog') {
                $taskData += ['percent_complete' => 0, 'status' => request('status')];
            } elseif (request('status') == 'In Progress') {
                $parentTask = Task::find($id);
                if (! $parentTask) {
                    Task::find($parentTask->parent_id)->update(['status' => 'In Progress']);
                }
                $taskData += ['status' => request('status')];
            } else {
                $taskData += ['percent_complete' => request('edit_percent_complete'), 'status' => request('status')];
            }
        }
        if (request('project_id') != request('edit_project_id')) {
            $taskData += [
                'project_id' => request('edit_project_id'),
            ];

            $task = Task::with('project')->where('project_id', request('edit_project_id'))->orderBy('id', 'DESC')->first();

            $project = Project::select('project_id')->where('id', request('edit_project_id'))->first();

            if ($task) {
                $last_id = explode('-', $task->code);
                $taskData += ['code' => $task->project->project_id.'-'.((int) end($last_id) + 1)];
            } else {
                $project = Project::where('id', request('edit_project_id'))->first();
                $taskData += ['code' => $project->project_id.'-1'];
            }
        }
        $taskToUpdate = Task::find($id);
        if (Gate::forUser(Auth::user())->denies('client-project-view', $taskToUpdate->project)) {
            abort(403);
        }
        $taskToUpdate->update($taskData);

        $newTask = Task::find($id);

        if ($request->hasFile('files')) {
            $files = $this->multipleUpload(request('files'));
        }

        foreach ($files as $file) {
            TaskDocument::create([
                'task_id' => $id,
                'path' => $file
            ]);
        }
        if (! Auth::user()->hasRole('client')) {
            $assignedUsers[] = $newTask->reviewer_id;
            $assignedUsers = array_filter($assignedUsers);
            $oldUsers = TaskAssignedUsers::select('user_id')->where('task_id', $id)->get();
            if (! empty(request('edit_task_assigned_users'))) {
                $assignedUsers = array_unique(array_merge($assignedUsers, request('edit_task_assigned_users')));
            }

            $this->terminateSession($assignedUsers, $oldUsers, $id);

            $users = $newTask->users;
            $newTask->users()->sync($assignedUsers);
            $users = $newTask->fresh()->users->diff($users);
            if ($users->count() > 0) {
                event(new UserTaskNotify($newTask, $users));
            }

            if (! empty(request('checklists'))) {
                $newTask->checklists()->sync(request('checklists'));
            }

            if (! empty(request('approval_users'))) {
                $newTask->approvers()->sync(request('approval_users'));
            }
        }

        $notificationUsers = $this->getTaskNotificationUsers($newTask->project_id);
        $taskCreator = User::active()->find($newTask->created_by);
        if ($taskCreator && (! $notificationUsers->contains('id', $taskCreator->id))) {
            $notificationUsers->push($taskCreator);
        }
        event(new TaskEditNotify($notificationUsers, $newTask->fresh()));
    }

    public function deleteTask($id)
    {
        $task = Task::find($id);
        if (Gate::forUser(Auth::user())->denies('client-project-view', $task->project)) {
            abort(403);
        }
        $oldUsers = TaskAssignedUsers::select('user_id')->where('task_id', $id)->get();
        $this->terminateSession([], $oldUsers, $id);
        $data = ['is_archived' => 1];
        $task->update($data);
        TaskAssignedUsersHour::where('task_id', $id)->delete();
        $notificationUsers = $this->getTaskNotificationUsers($task->project_id);
        $taskCreator = User::active()->find($task->created_by);
        if ($taskCreator && (! $notificationUsers->contains('id', $taskCreator->id))) {
            $notificationUsers->push($taskCreator);
        }
        event(new TaskDeleteNotify($notificationUsers, $task->fresh()));
    }

    public function editTaskApi($request, $id)
    {
        $currentTaskDetails = Task::where('id', request('task_id'))->first();

        $request->validate([
            'task_title' => 'required',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
            'estimated_time' => 'required'
        ]);

        $taskData = [
            'title' => request('task_title'),
            'priority' => request('task_priority'),
            'estimated_time' => request('estimated_time'),
            'actual_estimated_time' => request('actual_estimated_time'),
            'description' => request('task_description'),
            'task_url' => request('task_url'),
            'start_date' => Carbon::createFromFormat('d/m/Y', request('start_date'))->format('Y-m-d'),
            'end_date' => Carbon::createFromFormat('d/m/Y', request('end_date'))->format('Y-m-d'),
        ];

        if (request('status') == 'Done') {
            $taskData += ['percent_complete' => 100, 'status' => request('status')];
        } elseif (request('status') == 'Backlog') {
            $taskData += ['percent_complete' => 0, 'status' => request('status')];
        } else {
            $taskData += ['status' => request('status')];
        }

        if ($currentTaskDetails->project_id != request('project_id')) {
            $taskData += [
                'project_id' => request('project_id'),
            ];

            $task = Task::with('project')->where('project_id', request('project_id'))->orderBy('id', 'DESC')->first();

            $project = Project::select('project_id')->where('id', request('project_id'))->first();

            if ($task) {
                $last_id = explode('-', $task->code);
                $taskData += ['code' => $task->project->project_id.'-'.((int) end($last_id) + 1)];
            } else {
                $project = Project::where('id', request('project_id'))->first();
                $taskData += ['code' => $project->project_id.'-1'];
            }
        }

        Task::find($id)->update($taskData);
    }

    public function addUserDayHour($request, $task_id)
    {
        $estimateHour = request('estimated_time');
        $user_id = request('task_assigned_users')[0];
        $startDate = Carbon::createFromFormat('d/m/Y', request('start_date'))->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', request('end_date'))->format('Y-m-d');
        $currentDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        $user = User::where('id', $user_id)->first();
        $today = date('Y-m-d');
        while ($currentDate <= $endDate) {
            $date = date('Y-m-d', $currentDate);
            if (strtotime($date) >= strtotime($today)) {
                $checkDay = $user->checkAvailableDay($date);
                $checkDay = $checkDay->getData();
                if (! $checkDay->status) {
                    $hourSum = TaskAssignedUsersHour::where('user_id', $user_id)->where('date', $date)->sum('hour');
                    if ($hourSum < 8 && $estimateHour > 0) {
                        $assignHour = (8 - $hourSum);
                        $estimateHour = ($estimateHour - $assignHour);
                        $hoursToAssign[] = [
                            'user_id' => $user_id,
                            'task_id' => $task_id,
                            'hour' => ($estimateHour > 0) ? $hourSum + $assignHour : ($hourSum + $estimateHour + $assignHour),
                            'date' => $date,
                        ];
                    }
                } elseif ($checkDay->status && $checkDay->message == 'Leave' && $checkDay->data != 'Full Day') {
                    $hourSum = TaskAssignedUsersHour::where('user_id', $user_id)->where('date', $date)->sum('hour');
                    if ($hourSum < 4 && $estimateHour > 0) {
                        $assignHour = (4 - $hourSum);
                        $estimateHour = ($estimateHour - $assignHour);
                        $hoursToAssign[] = [
                            'user_id' => $user_id,
                            'task_id' => $task_id,
                            'hour' => ($estimateHour > 0) ? $hourSum + $assignHour : ($hourSum + $estimateHour + $assignHour),
                            'date' => $date,
                        ];
                    }
                }
            }
            $currentDate = strtotime('+1 day', $currentDate);
        }
        if ($estimateHour > 0) {
            return false;
        } else {
            foreach ($hoursToAssign as $hour) {
                TaskAssignedUsersHour::updateOrInsert(
                    ['user_id' => $hour['user_id'], 'date' => $hour['date'], 'task_id' => $hour['task_id']],
                    ['hour' => $hour['hour']]
                );
            }

            return true;
        }
    }

    public function multipleUpload(array $docs)
    {
        $files = [];
        foreach ($docs as $doc) {
            $docName = time().$doc->getClientOriginalName();
            $files[] = $doc->storeAs('tasks/documents', $docName);
        }

        return $files;
    }

    public function sendTaskCompleteNotification($task)
    {
        $users = User::projectAdmins()->get();
        $users->push($task->task_creator);
        $taskApprovers = $task->approvers->pluck('id')->toArray();

        foreach ($task->users as $user) {
            $users->push($user);
        }

        $users = $users->unique('id');
        foreach ($users as $key => $user) {
            if ($user->hasRole('administrator')) {
                if (in_array($user->id, $taskApprovers)) {
                    Notification::send($user, new TaskStatusNotification($user, $task, Auth::user()));
                }
            } else {
                Notification::send($user, new TaskStatusNotification($user, $task, Auth::user()));
            }
        }
    }

    public function convertDescriptionToComments($newTask)
    {
        if ($newTask->description != null) {
            $data = [
                'commenter_id' => auth()->user()->id,
                'commenter_type' => 'App\Models\User',
                'commentable_type' => 'App\Models\Task',
                'commentable_id' => $newTask->id,
                'comment' => $newTask->description,
                'approved' => 1

            ];
            $comment = Comment::create($data);
            $comment->commenter()->associate(auth()->user());
            $comment->commentable()->associate($newTask);
            $comment->save();
        }
    }

    public function terminateSession($newUsers, $oldUsers, $task_id)
    {
        foreach ($oldUsers as $user) {
            if (! in_array($user->user_id, $newUsers)) {
                $session = TaskSession::select('id')->where('user_id', 29)->where('task_id', 826)->where('current_status', 'started')->first();
                if ($session) {
                    $taskSession = TaskSession::select('id', 'start_time', 'total', 'comments')->where('task_id', $task_id)->where('user_id', $user->user_id)->where('created_at', 'like', '%'.date('Y-m-d').'%')->where('current_status', 'started')->first();

                    if ($taskSession) {
                        $start_time = new DateTime($taskSession->start_time);
                        $current_time = new DateTime(date('Y-m-d H:i:s'));
                        $diff = $start_time->diff($current_time);
                        $min = ($diff->h * 60) + $diff->i;

                        $data = [
                            'current_status' => 'over',
                            'total' => $taskSession->total + $min,
                            'end_time' => new DateTime(date('Y-m-d H:i:s')),
                            'billed_today' => 0,
                            'comments' => $taskSession->comments
                        ];

                        if ($taskSession->total + $min > 0) {
                            TaskSession::find($taskSession->id)->update($data);
                        } else {
                            TaskSession::find($taskSession->id)->delete();
                        }

                        $time_spent = TaskSession::where('task_id', $task_id)->sum('total');

                        $data = ['time_spent' => number_format($time_spent / 60, 2)];

                        if (number_format($time_spent) == 0) {
                            $data += ['status' => 'Backlog'];
                        }

                        Task::find($task_id)->update($data);
                    }
                }
            }
        }

        return true;
    }

    public function destroyTask($id)
    {
        $task = Task::find($id);
        if (Gate::forUser(Auth::user())->denies('client-project-view', $task->project)) {
            abort(403);
        }
        $oldUsers = TaskAssignedUsers::select('user_id')->where('task_id', $id)->get();
        $this->terminateSession([], $oldUsers, $id);
        if ($task->parent_id == null) {
            Task::where('parent_id', $id)->delete();
        }
        $task->delete();
    }

    public function enhanceMemoryLimit()
    {
        ini_set('memory_limit', '-1');
    }

    public function getClientProjects($isClient, $currentUserId)
    {
        return $this->taskRepository->getClientProjects($isClient, $currentUserId);
    }

    public function getTasks($isClient, $clientProjects)
    {
        return $this->taskRepository->getTasks($isClient, $clientProjects);
    }

    public function getSearchs()
    {
        return $this->taskRepository->getSearchs();
    }

    public function getParentTasks($task)
    {
        return $this->taskRepository->getParentTasks($task);
    }

    public function getParentTasksHasChildren($id)
    {
        return $this->taskRepository->getParentTasksHasChildren($id);
    }

    public function getExceedReasons($id)
    {
        return $this->taskRepository->getExceedReasons($id);
    }

    public function getCompletedSubTasks($id)
    {
        return $this->taskRepository->getCompletedSubTasks($id);
    }

    public function getArchivedTasks($clientProjects)
    {
        return $this->taskRepository->getArchivedTasks($clientProjects);
    }

    public function getSearchedProject($request)
    {
        $projectId = $request->search_project_name;
        if (! empty($projectId)) {
            return $this->taskRepository->getSearchedProject($projectId);
        }

        return [];
    }

    public function getSearchedTask($request)
    {
        $taskId = $request->search_task_name;
        if (! empty($taskId)) {
            return $this->taskRepository->getSearchedTask($taskId);
        }

        return [];
    }

    public function getProjects($clientProjects)
    {
        return $this->taskRepository->getProjects($clientProjects);
    }

    public function getTasksForArchivedTasksSearch($request)
    {
        $taskId = $request->search_task_name;
        $projectId = $request->search_project_name;
        $taskType = $request->search_task_type;
        $projectClient = $request->search_project_company;
        $status = $request->task_status;
        $assignedTo = $request->assigned_to;
        $filter = $request->filter;

        return $this->taskRepository->getTasksForArchivedTasksSearch($taskId, $projectId, $projectClient, $assignedTo, $status, $taskType, $filter, $request);
    }

    public function getUserNotClients()
    {
        return $this->taskRepository->getUserNotClients();
    }

    public function getTasksForSearchAgile($type)
    {
        return $this->taskRepository->getTasksForSearchAgile($type);
    }

    public function getDataForSearchAgile($status_types, $data)
    {
        return $this->taskRepository->getDataForSearchAgile($status_types, $data);
    }

    public function getDataForViewAgile($status_types, $data, $id)
    {
        return $this->taskRepository->getDataForViewAgile($status_types, $data, $id);
    }

    public function changeArchive()
    {
        return $this->taskRepository->changeArchive();
    }

    public function getAllTasks($projectId)
    {
        return $this->taskRepository->getAllTasks($projectId);
    }

    public function updateOrder()
    {
        $status = ucwords(str_replace('_', ' ', request('status')));

        $order = 'order_no';

        if (! empty(request('parent_id'))) {
            $order = 'sub_task_order';
        }

        $list = request('list');
        $i = 1;
        if (! empty($list)) {
            foreach ($list as $item) {
                $task = Task::with('users', 'approvers')->where('tasks.id', '=', $item)->first();
                if ($task->status != $status) {
                    event(new TaskStatusChangeNotify($task, $task->users, $task->reviewer, $task->approvers));
                }
                Task::find($item)->update([$order => $i, 'status' => $status]);
                $i = $i + 1;
            }
        }
    }

    public function getProjectsForAutocompleteData($request, $currentUserId, $isClient)
    {
        return $this->taskRepository->getProjectsForAutocompleteData($request, $currentUserId, $isClient);
    }

    public function getTasksForAutocompleteData($request, $currentUserId, $isClient)
    {
        return $this->taskRepository->getTasksForAutocompleteData($request, $currentUserId, $isClient);
    }

    public function getSearchedProjects($request)
    {
        return $this->taskRepository->getSearchedProjects($request);
    }

    public function getTasksForTaskSearch($request, $clientProjects)
    {
        return $this->taskRepository->getTasksForTaskSearch($request, $clientProjects);
    }

    public function getProjectsForTaskSearch($isClient, $clientProjects)
    {
        return $this->taskRepository->getProjectsForTaskSearch($isClient, $clientProjects);
    }

    public function canShowActualEstimateToUser()
    {
        $userRoleId = auth()->user()->role_id;
        $projectSettings = Settings::getProjectSettings();
        if (! isset($projectSettings['project_show_task_actual_estimate'])) {
            return 0;
        }
        $showActualEstimateSettings = $projectSettings['project_show_task_actual_estimate']->value;
        $showActualEstimateRoles = explode(',', $showActualEstimateSettings);

        if (in_array($userRoleId, $showActualEstimateRoles)) {
            return 1;
        } else {
            return 0;
        }
    }
}
