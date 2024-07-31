<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use App\Traits\GeneralTrait;

class ArchiveController extends Controller
{
    use GeneralTrait;

    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Retrieves archived projects, search results, and clients list for the current user.
     *
     * @return \Illuminate\View\View
     */
    public function archivedProjects()
    {
        $currentUserId = auth()->user()->id;
        $isClient = $this->isClient();

        $projects = $this->projectService->getArchiveProjects($isClient, $currentUserId);
        $searchs = $this->projectService->getArchiveSearchs($isClient, $currentUserId);
        $clientsList = $this->projectService->getClientsListArchive($isClient, $currentUserId);

        return view('projects.archived.index', compact('clientsList', 'projects', 'searchs'));
    }

    public function archivedProjectSearch()
    {
        $isClient = $this->isClient();
        $currentUserId = auth()->user()->id;

        $projects = $this->projectService->getProjectsForArchivedProjectSearch($isClient, $currentUserId);
        $searchs = $this->projectService->getSearchsForArchivedProjectSearch($isClient, $currentUserId);
        $clientsList = $this->projectService->getClientsListForArchivedProjectSearch($isClient, $currentUserId);

        return view('projects.archived.index', compact('clientsList', 'projects', 'searchs'));
    }

    public function changeArchiveProject()
    {
        $this->projectService->changeArchiveProject();
        $projects = $this->projectService->getNotArchivedProjects();
        $clientsList = $this->getClients();

        $content = view('projects.list', compact('clientsList', 'projects'))->render();
        $res = [
            'success' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }
}
