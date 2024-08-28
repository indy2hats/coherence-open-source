<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectCostService;
use App\Services\ProjectService;
use App\Services\TaskService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ActiveController extends Controller
{
    use GeneralTrait;

    protected $projectService;
    protected $taskService;

    public function __construct(ProjectService $projectService, TaskService $taskService)
    {
        $this->projectService = $projectService;
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentUserId = $this->getCurrentUserId();
        $isClient = $this->isClient();
        $tags = $this->projectService->getTags();
        $users = $this->projectService->getUserNotClients();
        $technologies = $this->projectService->getTechnologies();
        $projects = $this->projectService->getActiveProjects($isClient, $currentUserId);
        $searchs = $this->projectService->getSearchs($isClient, $currentUserId);
        $clientsList = $this->projectService->getClientsList($isClient, $currentUserId);

        return view('projects.index', compact('clientsList', 'projects', 'searchs', 'tags', 'users', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->createProject($request);
        $this->projectService->createProjectAssignedUsers($project->id);
        $projects = Project::with('client')->orderBy('project_name', 'ASC')->paginate(15)->withPath('');
        $clientsList = $this->getClients();

        $content = view('projects.list', compact('clientsList', 'projects'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Project created successfully',
            'data' => $content,
            'id' => $project->id
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
        $project = $this->projectService->getProject($id);
        if (! $project) {
            abort(404);
        }
        if (Gate::forUser(Auth::user())->denies('client-project-view', $project)) {
            abort(403);
        }

        $projectManagers = $this->projectService->getProjectManagers();
        $users = $this->projectService->getUsers();
        $admins = $this->projectService->getAdmins();
        $allTasks = $this->projectService->getAllTasks($id);
        $upcomingTasks = $this->projectService->getUpcomingTasks($id);
        $ongoingTasks = $this->projectService->getOngoingTasks($id);
        $completedTasks = $this->projectService->getCompletedTasks($id);
        $archivedTasks = $this->projectService->getArchivedTasks($id);
        $projectManagersData = $this->projectService->getProjectManagersData($id);
        $selectedProjectManagers = $this->projectService->getSelectedProjectManagers($projectManagersData);
        $tags = $this->projectService->getTags($projectManagersData);
        $parentTasks = $this->projectService->getParentTasks($id);
        $isKanbanView = $this->projectService->projectKanbanView();

        $estimatedTime = $project->task->sum('estimated_time');
        $actualEstimatedTime = $project->task->sum('actual_estimated_time');
        $timeSpent = number_format($project->task->sum('time_spent'), 2);
        $showActualEstimateToUser = $this->taskService->canShowActualEstimateToUser();

        return view('projects.view', compact('project', 'projectManagers', 'users', 'allTasks', 'parentTasks', 'upcomingTasks', 'ongoingTasks', 'completedTasks', 'archivedTasks', 'selectedProjectManagers', 'projectManagersData', 'estimatedTime', 'actualEstimatedTime', 'timeSpent', 'tags', 'admins', 'showActualEstimateToUser', 'isKanbanView'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = $this->projectService->getProjectWithUsers($id);

        $projectUsers = [];
        foreach ($project->projectUsers as $projectUser) {
            $projectUsers[] = $projectUser->id;
        }
        $clientsList = $this->projectService->getClientsList(false);
        $users = $this->projectService->getUserNotClients();
        $technologies = $this->projectService->getTechnologies();

        return view('projects.edit', compact('project', 'clientsList', 'projectUsers', 'users', 'technologies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        $this->projectService->updateProject($request, $id);
        $this->projectService->deleteProjectAssignedUsers($id);
        $this->projectService->createProjectAssignedUsers($id);

        $projects = $this->projectService->getNotArchivedProjects();
        $clientsList = $this->projectService->getClientsList(false);

        $content = view('projects.list', compact('clientsList', 'projects'))->render();
        $res = [
            'status' => 'ok',
            'message' => 'Project details updated successfully',
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
        $tasksCount = $this->projectService->getTasksCountForProject($id);
        if ($tasksCount > 0) {
            $res = [
                'status' => 'error',
                'message' => 'Cannot delete this project as there are tasks associated with this project.',
            ];
        } else {
            $this->projectService->deleteProject($id);
            $res = [
                'status' => 'success',
                'message' => 'Project deleted successfully',
            ];
        }

        return response()->json($res);
    }

    /**
     * Add/Update project managers for a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProjectManagersAjax(Request $request)
    {
        $projectId = request('project_id');
        $this->projectService->deleteProjectAssignedUsers($projectId);
        $this->projectService->createProjectManagers($projectId);
        $projectManagersData = $this->projectService->getProjectManagersData($projectId);

        $content = view('projects.project_managers', compact('projectManagersData'))->render();
        $res = [
            'status' => 'ok',
            'message' => 'Saved project users',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Get project managers for a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProjectManagersAjax(Request $request)
    {
        $projectManagersData = $this->projectService->getProjectManagersData(request('project_id'));

        return view('projects.project_managers', compact('projectManagersData'));
    }

    /**
     * Get project search parameters.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchProject()
    {
        $currentUserId = auth()->user()->id;
        $isClient = $this->isClient();

        $projects = $this->projectService->getProjectForProjectSearch($isClient, $currentUserId);
        $searchs = $this->projectService->getSearchsForProjectSearch($isClient, $currentUserId);
        $clientsList = $this->projectService->getClientsListForProjectSearch($isClient, $currentUserId);
        $tags = $this->projectService->getTags();
        $users = $this->projectService->getUserNotClients();
        $technologies = $this->projectService->getTechnologies();

        return view('projects.index', compact('clientsList', 'projects', 'searchs', 'tags', 'users', 'technologies'));
    }

    /**
     * Update the project details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProjectDetails()
    {
        $id = request('project_id');

        $project = $this->projectService->getProject($id);
        $users = $this->projectService->getUsers();
        $admins = $this->projectService->getAdmins();
        $projectManagers = $this->projectService->getUsersWithProjectManagerRole();
        $allTasks = $this->projectService->getAllTasks($id);
        $upcomingTasks = $this->projectService->getUpcomingTasks($id);
        $ongoingTasks = $this->projectService->getOngoingTasks($id);
        $completedTasks = $this->projectService->getCompletedTasks($id);
        $projectManagersData = $this->projectService->getProjectManagersData($id);
        $selectedProjectManagers = $this->projectService->getSelectedProjectManagers($projectManagersData);
        $tags = $this->projectService->getTags();
        $isKanbanView = $this->projectService->projectKanbanView();

        $estimatedTime = $project->task->sum('estimated_time');
        $actualEstimatedTime = $project->task->sum('actual_estimated_time');
        $timeSpent = $project->task->sum('time_spent');
        $showActualEstimateToUser = $this->taskService->canShowActualEstimateToUser();

        $content = view('projects.show', compact('project', 'projectManagers', 'users', 'admins', 'allTasks', 'upcomingTasks', 'ongoingTasks', 'completedTasks', 'selectedProjectManagers', 'projectManagersData', 'estimatedTime', 'actualEstimatedTime', 'timeSpent', 'tags', 'showActualEstimateToUser', 'isKanbanView'))->render();
        $res = [
            'status' => 'Saved',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Get the Project List.
     *
     * @return JSON
     */
    public function getList()
    {
        $projectList = $this->projectService->getProjectList();

        return response()->json($projectList);
    }

    /**
     * response for typeAhead function to list clients.
     *
     * @return JSON
     */
    public function getTypheadDataProject()
    {
        $typehead = $this->projectService->getTypehead();
        $project_type = $this->projectService->getProjectType();

        return response()->json(['data' => $typehead, 'type' => $project_type]);
    }

    /**
     * To generate code for Project ID.
     */
    public function generateCode($name)
    {
        return $this->projectService->generateCode($name);
    }

    /**
     * ajax function to load Project percentage of completion.
     *
     * @return JSON
     */
    public function loadProjectStatus()
    {
        $id = request('id');

        $overdueTasks = $this->projectService->getOverdueTasks($id);
        $pendingTasks = $this->projectService->getPendingTasks($id);
        $projectStatus = $this->projectService->getProjectStatus($id);

        return response()->json(['status' => $projectStatus, 'overdue' => $overdueTasks, 'pending' => $pendingTasks]);
    }

    /**
     * Get project cost details with filter.
     *
     * @param  Request  $request  The request data.
     * @return JSON
     */
    public function getProjectCostDetailsWithFilter(Request $request)
    {
        $response = ProjectCostService::getProjectCostDetails($request);

        return response()->json($response);
    }
}
