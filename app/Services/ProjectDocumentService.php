<?php

namespace App\Services;

use App\Repository\ProjectDocumentRepository;

class ProjectDocumentService
{
    protected $projectDocumentRepository;

    public function __construct(ProjectDocumentRepository $projectDocumentRepository)
    {
        $this->projectDocumentRepository = $projectDocumentRepository;
    }

    public function ajaxListProjectDocuments($id)
    {
        return $this->projectDocumentRepository->ajaxListProjectDocuments($id);
    }

    public function createProjectDocuments($request)
    {
        return $this->projectDocumentRepository->createProjectDocuments($request);
    }

    public function getProjectName($id)
    {
        return $this->projectDocumentRepository->getProjectName($id);
    }

    public function deleteProjectDocuments($id)
    {
        return $this->projectDocumentRepository->deleteProjectDocuments($id);
    }
}
