<?php

namespace App\Repository;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectAssignedUsers;
use App\Models\Settings;
use App\Models\Task;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;

class ProjectRepository
{
    use GeneralTrait{
        GeneralTrait::getTechnologies as traitGetTechnologies;
        GeneralTrait::getAdmins as traitGetAdmins;
        GeneralTrait::getTags as traitGetTags;
        GeneralTrait::getProjectManagersData as traitGetProjectManagersData;
        GeneralTrait::getUserNotClients as traitGetUserNotClients;
        GeneralTrait::updateProject as traitUpdateProject;
    }

    protected $model;

    public function __construct(Project $project)
    {
        $this->model = $project;
    }

    public function getActiveProjects($isClient, $currentUserId)
    {
        $projectsQuery = Project::with('client', 'technology')->orderBy('project_name', 'ASC');
        if ($isClient) {
            $projectsQuery->whereHas('client', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            });
        }

        return $projectsQuery->notArchived()->paginate(15);
    }

    public function getArchiveProjects($isClient, $currentUserId)
    {
        $projectsQuery = Project::with('client')->orderBy('project_name', 'ASC');
        if ($isClient) {
            $projectsQuery->whereHas('client', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            });
        }

        return $projectsQuery->isArchived()->paginate(15);
    }

    /**
     * A function to get search results for not archived projects based on the client status and current user ID.
     *
     * @param  bool  $isClient  Indicates if the user is a client
     * @param  int  $currentUserId  The ID of the current user
     * @return Illuminate\Database\Eloquent\Collection The collection of search results
     */
    public function getSearchs($isClient, $currentUserId)
    {
        $searchQuery = $this->getProjects($isClient, $currentUserId);

        return $searchQuery->notArchived()->get();
    }

    /**
     * Retrieves the search results for archived projects based on the client status and current user ID.
     *
     * @param  bool  $isClient  Indicates if the user is a client
     * @param  int  $currentUserId  The ID of the current user
     * @return \Illuminate\Database\Eloquent\Collection The collection of search results for archived projects
     */
    public function getArchiveSearchs($isClient, $currentUserId)
    {
        $searchQuery = $this->getProjects($isClient, $currentUserId);

        return $searchQuery->isArchived()->get();
    }

    /**
     * A function to get projects based on the client status and current user ID.
     *
     * @param  bool  $isClient  Indicates if the user is a client
     * @param  int  $currentUserId  The ID of the current user
     * @return Illuminate\Database\Eloquent\Builder The query builder for projects
     */
    public function getProjects($isClient, $currentUserId)
    {
        $searchQuery = Project::with('client')->orderBy('project_name', 'ASC');
        if ($isClient) {
            $searchQuery->whereHas('client', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            });
        }

        return $searchQuery;
    }

    public function getClientsList($isClient, $currentUserId)
    {
        return $this->getClientsListByUserId($isClient, $currentUserId);
    }

    public function getClientsListArchive($isClient, $currentUserId)
    {
        $clientsQuery = Client::select('id', 'company_name')->orderBy('company_name', 'ASC');

        if ($isClient) {
            $clientsQuery->where('user_id', $currentUserId);
        }

        return $clientsQuery->get();
    }

    public function getStartDate()
    {
        return Carbon::createFromFormat('d/m/Y', request('start_date'))->format('Y-m-d');
    }

    public function getEndDate()
    {
        return Carbon::createFromFormat('d/m/Y', request('end_date'))->format('Y-m-d');
    }

    public function createProject($request, $projectId)
    {
        $data = [
            'project_name' => request('project_name'),
            'client_id' => request('client'),
            'project_id' => $projectId,
            'project_type' => request('project_type'),
            'start_date' => $this->getStartDate(),
            'end_date' => request('end_date') != null ? $this->getEndDate() : '',
            'cost_type' => request('cost_type'),
            'rate' => request('rate'),
            'estimated_hours' => request('estimated_hours'),
            'priority' => request('priority'),
            'description' => request('description'),
            'status' => 'Active',
            'site_url' => request('site_url'),
            'category' => request('category'),
            'technology_id' => request('technology')
        ];

        $project = Project::create($data);

        return $project;
    }

    public function getStartDateForUpdateProject()
    {
        return Carbon::createFromFormat('d/m/Y', request('edit_start_date'))->format('Y-m-d');
    }

    public function getEndDateForUpdateProject()
    {
        return Carbon::createFromFormat('d/m/Y', request('edit_end_date'))->format('Y-m-d');
    }

    public function updateProject($request, $id)
    {
        $data = [
            'project_name' => request('edit_project_name'),
            'client_id' => request('edit_client'),
            'project_id' => request('edit_project_id'),
            'project_type' => request('edit_project_type'),
            'start_date' => $this->getStartDateForUpdateProject(),
            'end_date' => ($request->edit_end_date !== '') ? $this->getEndDateForUpdateProject() : '',
            'cost_type' => request('edit_cost_type'),
            'rate' => request('edit_rate'),
            'estimated_hours' => request('edit_estimated_hours'),
            'priority' => request('edit_priority'),
            'description' => request('edit_description'),
            'status' => request('edit_status'),
            'site_url' => request('site_url'),
            'category' => request('edit_category'),
            'technology_id' => request('edit_technology')
        ];

        if ($request->has('is_archived')) {
            $data += ['is_archived' => 1];
        } else {
            $data += ['is_archived' => 0];
        }

        $this->traitUpdateProject($id, $data);

        $data = ['is_archived' => $request->has('is_archived') ? 1 : 0];

        Task::where('project_id', $id)->update($data);
    }

    public function getUsers()
    {
        return User::notClients()->select('id', 'first_name', 'last_name')->orderBy('first_name', 'ASC')->get();
    }

    public function getAdmins()
    {
        return $this->traitGetAdmins();
    }

    public function getAllTasks($id)
    {
        return Task::alltask($id)->parents()->orderBy('updated_at', 'DESC')->get();
    }

    public function getUpcomingTasks($id)
    {
        return Task::upcomingtask($id)->parents()->orderBy('updated_at', 'DESC')->get();
    }

    public function getOngoingTasks($id)
    {
        return Task::ongoingtask($id)->parents()->orderBy('updated_at', 'DESC')->get();
    }

    public function getCompletedTasks($id)
    {
        return Task::completedtask($id)->parents()->orderBy('updated_at', 'DESC')->get();
    }

    public function getArchivedTasks($id)
    {
        return Task::archivedTask($id)->parents()->where('is_archived', 1)->orderBy('updated_at', 'DESC')->get();
    }

    public function getOverdueTasks($id)
    {
        return Task::where('project_id', $id)->where(function ($query) {
            $query->whereRaw('estimated_time < time_spent')->orWhere('end_date', '<', date('Y-m-d'));
        })->whereIn('status', config('overdue-status'))->count();
    }

    public function getPendingTasks($id)
    {
        return Task::where('project_id', $id)->where('status', '!=', 'Done')->where('is_archived', 0)->count();
    }

    public function getProject($id)
    {
        return Project::with('client')->with('task')->where('id', $id)->first();
    }

    public function getProjectManagers()
    {
        return User::notClients()->orderBy('first_name', 'ASC')->get();
    }

    public function getProjectManagersData($id)
    {
        return $this->traitGetProjectManagersData($id);
    }

    public function getTags()
    {
        return $this->traitGetTags();
    }

    public function getParentTasks($id)
    {
        return Task::where([
            'project_id' => $id,
            'parent_id' => null,
        ])->notArchived()->orderBy('title', 'Asc')->get();
    }

    public function getTechnologies()
    {
        return $this->traitGetTechnologies();
    }

    public function deleteProjectAssignedUsers($id)
    {
        ProjectAssignedUsers::where('project_id', $id)->delete();
    }

    public function getTasksCountForProject($id)
    {
        return Task::where('project_id', $id)->count();
    }

    public function deleteProject($id)
    {
        Project::find($id)->delete();
    }

    public function getUsersWithProjectManagerRole()
    {
        return User::select('id', 'first_name', 'last_name')
                ->whereHas('role', function ($q) {
                    $q->where('name', '=', 'project-manager');
                })->orderBy('first_name', 'ASC')->get();
    }

    public function getUserNotClients()
    {
        return $this->traitGetUserNotClients();
    }

    public function getProjectList()
    {
        return Project::select('project_name as name')->get();
    }

    public function getTypehead()
    {
        return Project::with('client')->distinct('project_type')->get();
    }

    public function getProjectType()
    {
        return Project::select('project_type')->distinct('project_type')->get();
    }

    public function changeArchiveProject()
    {
        $data = ['is_archived' => request('is_archived') == 'true' ? 1 : 0];

        $this->traitUpdateProject(request('id'), $data);

        Task::where('project_id', request('id'))->update($data);
    }

    public function getNotArchivedProjects()
    {
        return Project::with('client')->orderBy('project_name', 'ASC')->notArchived()->paginate(15)->withPath('');
    }

    public function getProjectsForArchivedProjectSearch($isClient, $currentUserId)
    {
        $projectClient = request()->search_project_company;
        $projectId = request()->search_project_name;
        $projectPriority = request()->projectPriority;
        $projectCategory = request()->projectCategory;

        $projectSearchQuery = Project::with('client');

        if ($isClient) {
            $projectSearchQuery->whereHas('client', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            });
        }

        $projectSearchQuery->when(! empty($projectClient), function ($q) use ($projectClient) {
            return $q->with(['client' => function ($q) use ($projectClient) {
                $q->where('id', $projectClient);
            }])->whereHas('client', function ($query) use ($projectClient) {
                $query->where('id', $projectClient);
            });
        });
        $projectSearchQuery->when(! empty($projectId), function ($q) use ($projectId) {
            return $q->where('id', $projectId);
        });
        $projectSearchQuery->when(! empty($projectPriority), function ($q) use ($projectPriority) {
            return $q->where('priority', '=', $projectPriority);
        });

        $projectSearchQuery->when(! empty($projectCategory), function ($q) use ($projectCategory) {
            return $q->where('category', $projectCategory);
        });

        $projects = $projectSearchQuery->isArchived()->orderBy('project_name', 'ASC')->paginate(15)->setPath('');

        $projects->appends(['search_project_company' => request()->search_project_company, 'search_project_name' => request()->search_project_name, 'projectPriority' => request()->projectPriority, 'projectCategory' => request()->projectCategory]);

        return $projects;
    }

    public function getSearchsForArchivedProjectSearch($isClient, $currentUserId)
    {
        $searchs = Project::with('client');
        if ($isClient) {
            $searchs->whereHas('client', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            })->notArchived();
        }

        return $searchs->orderBy('project_name', 'ASC')->isArchived()->get();
    }

    public function getClientsListForArchivedProjectSearch($isClient, $currentUserId)
    {
        $clientsList = Client::select('id', 'company_name');
        if ($isClient) {
            $clientsList->where('user_id', $currentUserId);
        }

        return $clientsList->orderBy('company_name', 'ASC')->get();
    }

    public function getProjectForProjectSearch($isClient, $currentUserId)
    {
        $projectClient = request()->search_project_company;
        $projectId = request()->search_project_name;
        $projectTechnology = request()->projectTechnology;
        $projectPriority = request()->projectPriority;
        $projectCategory = request()->projectCategory;

        $projectSearchQuery = Project::with('client');

        if ($isClient) {
            $projectSearchQuery->whereHas('client', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            });
        }

        $projectSearchQuery->when(! empty($projectClient), function ($q) use ($projectClient) {
            return $q->with(['client' => function ($q) use ($projectClient) {
                $q->where('id', $projectClient);
            }])->whereHas('client', function ($query) use ($projectClient) {
                $query->where('id', $projectClient);
            });
        });
        $projectSearchQuery->when(! empty($projectId), function ($q) use ($projectId) {
            return $q->where('id', $projectId);
        });

        $projectSearchQuery->when(! empty($projectTechnology), function ($q) use ($projectTechnology) {
            return $q->where('technology_id', '=', $projectTechnology);
        });

        $projectSearchQuery->when(! empty($projectPriority), function ($q) use ($projectPriority) {
            return $q->where('priority', '=', $projectPriority);
        });

        $projectSearchQuery->when(! empty($projectCategory), function ($q) use ($projectCategory) {
            return $q->where('category', $projectCategory);
        });

        $projects = $projectSearchQuery->notArchived()->orderBy('project_name', 'ASC')->paginate(15)->setPath('');

        $projects->appends(['search_project_company' => request()->search_project_company, 'search_project_name' => request()->search_project_name, 'projectPriority' => request()->projectPriority, 'projectCategory' => request()->projectCategory, 'projectTechnology' => request()->projectTechnology]);

        return $projects;
    }

    public function getSearchsForProjectSearch($isClient, $currentUserId)
    {
        $searchs = Project::with('client');

        if ($isClient) {
            $searchs->whereHas('client', function ($q) use ($currentUserId) {
                $q->where('user_id', $currentUserId);
            })->notArchived();
        }

        return $searchs->orderBy('project_name', 'ASC')->notArchived()->get();
    }

    public function getClientsListForProjectSearch($isClient, $currentUserId)
    {
        $clientsList = Client::select('id', 'company_name');

        if ($isClient) {
            $clientsList->where('user_id', $currentUserId);
        }

        return $clientsList->orderBy('company_name', 'ASC')->get();
    }

    public function getProjectWithUsers($id)
    {
        return Project::with('projectUsers')->where('id', $id)->first();
    }

    public function projectKanbanView()
    {
        return Settings::where('slug', 'project_kanban_view')->value('value');
    }
}
