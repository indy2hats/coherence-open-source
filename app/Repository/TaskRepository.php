<?php

namespace App\Repository;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAssignedUsers;
use App\Models\User;
use App\Traits\GeneralTrait;

class TaskRepository
{
    use GeneralTrait;

    protected $model;

    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    public function getClientProjects($isClient, $currentUserId)
    {
        $clientProjects = [];

        if ($isClient) {
            $clientProjects = Project::whereHas('client', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            })->pluck('id')->toArray();
        }

        return $clientProjects;
    }

    public function getTasks($isClient, $clientProjects)
    {
        $tasksQuery = $this->model::with('users', 'project', 'taskTag')->has('project')->orderBy('updated_at', 'DESC');

        if ($isClient) {
            $tasksQuery->whereIn('project_id', $clientProjects);
        }

        return $tasksQuery->notArchived()->paginate(15);
    }

    public function getSearchs()
    {
        return $this->model::with('users', 'project')->orderBy('updated_at', 'DESC')->has('project')->get();
    }

    public function getParentTasks($task)
    {
        return $this->model::where([
            'project_id' => $task->project_id,
            'parent_id' => null,
            ['id', '<>', $task->id]
        ])->notArchived()->orderBy('title', 'Asc')->get();
    }

    public function getParentTasksHasChildren($id)
    {
        return $this->model::where('parent_id', '=', null)->where('project_id', $id)->has('children')->get();
    }

    public function getExceedReasons($id)
    {
        return TaskAssignedUsers::with('user')->where('task_id', $id)->get();
    }

    public function getCompletedSubTasks($id)
    {
        return $this->model::where('parent_id', $id)->completedtask()->orderBy('updated_at', 'DESC')->get();
    }

    public function getArchivedTasks($clientProjects)
    {
        $tasksQuery = $this->model::with('users', 'taskTag')->has('project')->orderBy('updated_at', 'DESC');
        if (! empty($clientProjects)) {
            $tasksQuery->whereIn('project_id', $clientProjects);
        }

        return $tasksQuery->isArchived()->paginate(15);
    }

    public function getSearchedProject($projectId)
    {
        return Project::select('id', 'project_name')->where('id', $projectId)->first();
    }

    public function getSearchedProjects($request)
    {
        $projectIds = $request->search_project_name;
        if (! empty($projectIds)) {
            return Project::select('id', 'project_name')->wherein('id', $projectIds)->get();
        }

        return [];
    }

    public function getSearchedTask($taskId)
    {
        return $this->model::select('id', 'title')->where('id', $taskId)->first();
    }

    public function getProjects($clientProjects)
    {
        $projectsQuery = Project::orderBy('project_name', 'ASC');
        if (! empty($clientProjects)) {
            $projectsQuery->whereIn('id', $clientProjects);
        }

        return $projectsQuery->get();
    }

    public function getTasksForArchivedTasksSearch($taskId, $projectId, $projectClient, $assignedTo, $status, $taskType, $filter, $request)
    {
        $projectSearchQuery = $this->model::with('users')->has('project');

        $projectSearchQuery->when(! empty($taskId), function ($q) use ($taskId) {
            return $q->where('id', $taskId);
        });

        $projectSearchQuery->when(! empty($projectId), function ($q) use ($projectId) {
            return $q->with(['project' => function ($q) use ($projectId) {
                $q->where('id', '=', $projectId);
            }])->whereHas('project', function ($query) use ($projectId) {
                $query->where('id', '=', $projectId);
            });
        });

        $projectSearchQuery->when(! empty($projectClient), function ($q) use ($projectClient) {
            return $q->with(['project.client' => function ($q) use ($projectClient) {
                $q->where('id', '=', $projectClient);
            }])->whereHas('project.client', function ($query) use ($projectClient) {
                $query->where('id', '=', $projectClient);
            });
        });

        $projectSearchQuery->when(! empty($assignedTo), function ($q) use ($assignedTo) {
            return $q->with(['users' => function ($q) use ($assignedTo) {
                $q->where('user_id', '=', $assignedTo);
            }])->whereHas('users', function ($query) use ($assignedTo) {
                $query->where('user_id', '=', $assignedTo);
            });
        });

        $projectSearchQuery->when(! empty($status), function ($q) use ($status) {
            return $q->where('status', $status);
        });

        $projectSearchQuery->when(! empty($taskType), function ($q) use ($taskType) {
            if ($taskType == 'upcomming') {
                $q->where('status', 'Backlog');
            } elseif ($taskType == 'ongoing') {
                $q->where('status', '!=', 'Done')->where('status', '!=', 'Backlog');
            } elseif ($taskType == 'completed') {
                $q->where('status', 'Done');
            } elseif ($taskType == 'overdue') {
                $q->where('end_date', '<', date('Y-m-d'))->whereRaw('estimated_time < time_spent')->whereIn('status', config('overdue-status'));
            }
        });

        $projectSearchQuery->when(! empty($filter), function ($q) use ($filter) {
            if ($filter == 'Created Date') {
                $q->orderBy('created_at', 'DESC');
            } elseif ($filter == 'Deadline') {
                $q->orderBy('end_date', 'DESC');
            } elseif ($filter == 'Time Spent') {
                $q->orderByRaw('cast(time_spent AS DECIMAL(10,2)) DESC');
            }
        });

        $projectSearchQuery->when(empty($filter), function ($q) {
            $q->orderBy('created_at', 'DESC');
        });

        $tasks = $projectSearchQuery->with('users')->isArchived()->paginate(15)->setPath('');

        $tasks->appends(['search_task_name' => $request->search_task_name, 'search_project_name' => $request->search_project_name, 'search_task_type' => $request->search_task_type, 'task_status' => $request->task_status, 'search_project_company' => $request->search_project_company, 'assigned_to' => $request->assigned_to, 'filter' => $request->filter]);

        return $tasks;
    }

    public function getUserNotClients()
    {
        return User::notClients()->select('id', 'first_name', 'last_name')->orderBy('first_name', 'ASC')->get();
    }

    public function getTasksForSearchAgile($type)
    {
        $set = [];
        $tasks = $this->model::where('parent_id', request('task_id'))->where('status', $type->title)->where('add_to_board', 1)->orderBy('sub_task_order', 'ASC')->get();
        foreach ($tasks as $task) {
            array_push($set, ['task_id' => $task->id, 'task_title' => $task->title, 'priority' => $task->priority, 'deadline' => $task->end_date_format, 'estimated_time' => $task->estimated_time, 'time_spent' => $task->time_spent]);
        }

        return $set;
    }

    public function getTasksForViewAgile($id, $type)
    {
        $set = [];
        $tasks = $this->model::where('project_id', $id)->where('parent_id', '=', null)->where('status', $type->title)->where('add_to_board', 1)->where('is_archived', 0)->orderBy('order_no', 'ASC')->get();
        foreach ($tasks as $task) {
            array_push($set, ['task_id' => $task->id, 'task_title' => $task->title, 'priority' => $task->priority, 'deadline' => $task->end_date_format, 'estimated_time' => $task->estimated_time, 'actual_estimated_time' => $task->actual_estimated_time, 'time_spent' => $task->time_spent]);
        }

        return $set;
    }

    public function getDataForSearchAgile($status_types, $data)
    {
        foreach ($status_types as $type) {
            if ($this->isEmployee() && ! in_array($type->title, ['Backlog', 'In Progress', 'Development Completed', 'Under QA'])) {
                continue;
            }

            $set = $this->getTasksForSearchAgile($type);
            array_push($data, ['type' => $type->title, 'tasks' => $set]);
        }

        return $data;
    }

    public function getDataForViewAgile($status_types, $data, $id)
    {
        foreach ($status_types as $type) {
            if ($this->isEmployee() && ! in_array($type->title, ['Backlog', 'In Progress', 'Development Completed', 'Under QA'])) {
                continue;
            }
            $set = [];
            $set = $this->getTasksForViewAgile($id, $type);
            array_push($data, ['type' => $type->title, 'tasks' => $set]);
        }

        return $data;
    }

    public function changeArchive()
    {
        $data = ['is_archived' => request('is_archived') == 'true' ? 1 : 0];
        $this->model::find(request('id'))->update($data);
    }

    public function getAllTasks($projectId)
    {
        return $this->model::alltask($projectId)->select('title', 'id')->notArchived()
                    ->where('parent_id', null)
                    ->orderBy('title', 'Asc')->get();
    }

    public function getProjectsForAutocompleteData($request, $currentUserId, $isClient)
    {
        $projectSearchTerm = $request->get('term');

        $projectsQuery = Project::select('id', 'project_name')
            ->with('client')->where('project_name', 'like', '%'.$projectSearchTerm.'%');
        if ($isClient) {
            $projectsQuery->whereHas('client', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            });
            $projects = $projectsQuery->orderBy('project_name', 'ASC')->get();
        } else {
            $projects = $projectsQuery->orderBy('project_name', 'ASC')->get();
        }

        return $projects;
    }

    public function getTasksForAutocompleteData($request, $currentUserId, $isClient)
    {
        $tasks = [];

        $taskSearchTerm = $request->get('term');
        $projectIds = $request->get('project_ids');

        $tasksQuery = $this->model::select('id', 'title')->where('title', 'like', '%'.$taskSearchTerm.'%')->has('project');

        if ($projectIds) {
            $tasksQuery->whereIn('project_id', $projectIds);
        }
        if ($isClient) {
            $clientProjects = Project::whereHas('client', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            })->pluck('id')->toArray();

            if (! empty($clientProjects)) {
                $tasksQuery->whereIn('project_id', $clientProjects);
                $tasks = $tasksQuery->orderBy('title', 'ASC')->get();
            }
        } else {
            $tasks = $tasksQuery->orderBy('title', 'ASC')->get();
        }

        return $tasks;
    }

    public function getTasksForTaskSearch($request, $clientProjects)
    {
        $taskId = $request->search_task_name;
        $projectIds = $request->search_project_name;
        $projectSearchQuery = $this->model::with('users')->has('project');
        $projectClient = $request->search_project_company;
        $assignedTo = $request->assigned_to;
        $status = $request->task_status;
        $taskType = $request->search_task_type;
        $filter = $request->filter;

        $projectSearchQuery->when(! empty($clientProjects), function ($q) use ($clientProjects) {
            return $q->whereIn('project_id', $clientProjects);
        });

        $projectSearchQuery->when(! empty($taskId), function ($q) use ($taskId) {
            return $q->where('id', $taskId);
        });

        $projectSearchQuery->when(! empty($projectIds), function ($q) use ($projectIds) {
            return $q->with(['project' => function ($q) use ($projectIds) {
                $q->whereIn('id', $projectIds);
            }])->whereHas('project', function ($query) use ($projectIds) {
                $query->whereIn('id', $projectIds);
            });
        });

        $projectSearchQuery->when(! empty($projectClient), function ($q) use ($projectClient) {
            return $q->with(['project.client' => function ($q) use ($projectClient) {
                $q->where('id', '=', $projectClient);
            }])->whereHas('project.client', function ($query) use ($projectClient) {
                $query->where('id', '=', $projectClient);
            });
        });

        $projectSearchQuery->when(! empty($assignedTo), function ($q) use ($assignedTo) {
            return $q->with(['users' => function ($q) use ($assignedTo) {
                $q->where('user_id', '=', $assignedTo);
            }])->whereHas('users', function ($query) use ($assignedTo) {
                $query->where('user_id', '=', $assignedTo);
            });
        });

        $projectSearchQuery->when(! empty($status), function ($q) use ($status) {
            return $q->where('status', $status);
        });

        $projectSearchQuery->when(! empty($taskType), function ($q) use ($taskType) {
            if ($taskType == 'upcomming') {
                $q->where('status', 'Backlog');
            } elseif ($taskType == 'ongoing') {
                $q->where('status', '!=', 'Done')->where('status', '!=', 'Backlog');
            } elseif ($taskType == 'completed') {
                $q->where('status', 'Done');
            } elseif ($taskType == 'overdue') {
                $q->where('end_date', '<', date('Y-m-d'))->whereRaw('estimated_time < time_spent')->whereIn('status', config('overdue-status'));
            }
        });

        $projectSearchQuery->when(! empty($filter), function ($q) use ($filter) {
            if ($filter == 'Created Date') {
                $q->orderBy('created_at', 'DESC');
            } elseif ($filter == 'Deadline') {
                $q->orderBy('end_date', 'DESC');
            } elseif ($filter == 'Time Spent') {
                $q->orderByRaw('cast(time_spent AS DECIMAL(10,2)) DESC');
            }
        });

        $projectSearchQuery->when(empty($filter), function ($q) {
            $q->orderBy('created_at', 'DESC');
        });

        $tasks = $projectSearchQuery->with('users')->notArchived()->paginate(15)->setPath('');

        $tasks->appends(['search_task_name' => $request->search_task_name, 'search_project_name' => $request->search_project_name, 'search_task_type' => $request->search_task_type, 'task_status' => $request->task_status, 'search_project_company' => $request->search_project_company, 'assigned_to' => $request->assigned_to, 'filter' => $request->filter]);

        return $tasks;
    }

    public function getProjectsForTaskSearch($isClient, $clientProjects)
    {
        $projectsQuery = Project::orderBy('project_name', 'ASC');
        if ($isClient) {
            $projectsQuery->whereIn('id', $clientProjects);
        }

        return $projectsQuery->get();
    }
}
