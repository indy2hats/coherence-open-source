<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectAssignedUsers;
use App\Models\Task;
use App\Repository\ProjectRepository;

class ProjectService
{
    protected $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function getActiveProjects($isClient, $currentUserId)
    {
        return $this->projectRepository->getActiveProjects($isClient, $currentUserId);
    }

    public function getArchiveProjects($isClient, $currentUserId)
    {
        return $this->projectRepository->getArchiveProjects($isClient, $currentUserId);
    }

    public function getSearchs($isClient, $currentUserId)
    {
        return $this->projectRepository->getSearchs($isClient, $currentUserId);
    }

    public function getArchiveSearchs($isClient, $currentUserId)
    {
        return $this->projectRepository->getArchiveSearchs($isClient, $currentUserId);
    }

    public function getClientsList($isClient, $currentUserId = null)
    {
        return $this->projectRepository->getClientsList($isClient, $currentUserId);
    }

    public function getClientsListArchive($isClient, $currentUserId)
    {
        return $this->projectRepository->getClientsListArchive($isClient, $currentUserId);
    }

    public function createProject($request)
    {
        $projectId = $this->generateCode(request('project_name'));

        return $this->projectRepository->createProject($request, $projectId);
    }

    public function updateProject($request, $id)
    {
        return $this->projectRepository->updateProject($request, $id);
    }

    public function createProjectAssignedUsers($id)
    {
        $assignedUsers = request('project_assigned_users');
        if (is_array($assignedUsers) && count($assignedUsers) > 0) {
            foreach ($assignedUsers as $assignedUser) {
                $projectManagerData = [
                    'project_id' => $id,
                    'user_id' => $assignedUser
                ];
                ProjectAssignedUsers::create($projectManagerData);
            }
        }
    }

    public function createProjectManagers($projectId)
    {
        $projectManagers = request('project_managers');
        if ($projectManagers != null) {
            foreach ($projectManagers as $projectManagerId) {
                $projectManagerData = [
                    'project_id' => $projectId,
                    'user_id' => $projectManagerId
                ];

                ProjectAssignedUsers::create($projectManagerData);
            }
        }
    }

    public function deleteProjectAssignedUsers($id)
    {
        return $this->projectRepository->deleteProjectAssignedUsers($id);
    }

    public function getProject($id)
    {
        return $this->projectRepository->getProject($id);
    }

    public function getProjectManagers()
    {
        return $this->projectRepository->getProjectManagers();
    }

    public function getUsers()
    {
        return $this->projectRepository->getUsers();
    }

    public function getAdmins()
    {
        return $this->projectRepository->getAdmins();
    }

    public function getAllTasks($id)
    {
        return $this->projectRepository->getAllTasks($id);
    }

    public function getUpcomingTasks($id)
    {
        return $this->projectRepository->getUpcomingTasks($id);
    }

    public function getOngoingTasks($id)
    {
        return $this->projectRepository->getOngoingTasks($id);
    }

    public function getCompletedTasks($id)
    {
        return $this->projectRepository->getCompletedTasks($id);
    }

    public function getArchivedTasks($id)
    {
        return $this->projectRepository->getArchivedTasks($id);
    }

    public function getOverdueTasks($id)
    {
        return $this->projectRepository->getOverdueTasks($id);
    }

    public function getPendingTasks($id)
    {
        return $this->projectRepository->getPendingTasks($id);
    }

    public function getProjectManagersData($id)
    {
        return $this->projectRepository->getProjectManagersData($id);
    }

    public function getSelectedProjectManagers($projectManagersData)
    {
        $selectedProjectManagers = [];
        foreach ($projectManagersData as $projectManager) {
            $selectedProjectManagers[] = $projectManager->user_id;
        }

        return $selectedProjectManagers;
    }

    public function getTags()
    {
        return $this->projectRepository->getTags();
    }

    public function getParentTasks($id)
    {
        return $this->projectRepository->getParentTasks($id);
    }

    public function getTechnologies()
    {
        return $this->projectRepository->getTechnologies();
    }

    public function getTasksCountForProject($id)
    {
        return $this->projectRepository->getTasksCountForProject($id);
    }

    public function deleteProject($id)
    {
        return $this->projectRepository->deleteProject($id);
    }

    public function getUsersWithProjectManagerRole()
    {
        return $this->projectRepository->getUsersWithProjectManagerRole();
    }

    public function getProjectList()
    {
        return $this->projectRepository->getProjectList();
    }

    public function getTypehead()
    {
        return $this->projectRepository->getTypehead();
    }

    public function getProjectType()
    {
        return $this->projectRepository->getProjectType();
    }

    public function generateCode($name)
    {
        $words = explode(' ', $name);

        if (count($words) > 1) {
            $characterCountIndex = 0;
            do {
                $code = '';
                foreach ($words as $word) {
                    for ($index = 0; $index <= $characterCountIndex; $index++) {
                        if (isset($word[$index])) {
                            $code .= $word[$index];
                        }
                    }
                }
                $characterCountIndex++;
            } while ($this->checkProjectCodeExists($code));

            return strtoupper($code);
        }

        //For single word project names
        $characterCount = 4;
        $code = '';
        do {
            if (($characterCount >= strlen($name)) && ($code == $name)) {
                $code .= rand(0, 9);
            } else {
                $code = substr($name, 0, $characterCount);
                $characterCount++;
            }
        } while ($this->checkProjectCodeExists($code));

        return strtoupper($code);
    }

    public function checkProjectCodeExists($generatedCode)
    {
        if (Project::withTrashed()->where('project_id', $generatedCode)->count() > 0) {
            return true;
        }

        return false;
    }

    public function getProjectStatus($id)
    {
        $projectStatus = Task::where('project_id', $id)->avg('percent_complete');
        $projectStatus = $projectStatus == null ? 0 : $projectStatus;

        return $projectStatus;
    }

    public function changeArchiveProject()
    {
        $this->projectRepository->changeArchiveProject();
    }

    public function getNotArchivedProjects()
    {
        return $this->projectRepository->getNotArchivedProjects();
    }

    public function getProjectsForArchivedProjectSearch($isClient, $currentUserId)
    {
        return $this->projectRepository->getProjectsForArchivedProjectSearch($isClient, $currentUserId);
    }

    public function getSearchsForArchivedProjectSearch($isClient, $currentUserId)
    {
        return $this->projectRepository->getSearchsForArchivedProjectSearch($isClient, $currentUserId);
    }

    public function getClientsListForArchivedProjectSearch($isClient, $currentUserId)
    {
        return $this->projectRepository->getClientsListForArchivedProjectSearch($isClient, $currentUserId);
    }

    public function getProjectForProjectSearch($isClient, $currentUserId)
    {
        return $this->projectRepository->getProjectForProjectSearch($isClient, $currentUserId);
    }

    public function getSearchsForProjectSearch($isClient, $currentUserId)
    {
        return $this->projectRepository->getSearchsForProjectSearch($isClient, $currentUserId);
    }

    public function getClientsListForProjectSearch($isClient, $currentUserId)
    {
        return $this->projectRepository->getClientsListForProjectSearch($isClient, $currentUserId);
    }

    public function getUserNotClients()
    {
        return $this->projectRepository->getUserNotClients();
    }

    public function getProjectWithUsers($id)
    {
        return $this->projectRepository->getProjectWithUsers($id);
    }
}
