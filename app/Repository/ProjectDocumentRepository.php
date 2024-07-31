<?php

namespace App\Repository;

use App\Models\Project;
use App\Models\ProjectDocuments;
use App\Traits\GeneralTrait;

class ProjectDocumentRepository
{
    use GeneralTrait;

    protected $model;

    public function __construct(ProjectDocuments $projectDocuments)
    {
        $this->model = $projectDocuments;
    }

    public function ajaxListProjectDocuments($id)
    {
        return $this->model::with('project')->where('project_id', $id)->get();
    }

    public function createProjectDocuments($request)
    {
        $data = [];

        if (request('type') == 'link') {
            $data = [
                'project_id' => request('project_id'),
                'type' => 'link',
                'name' => request('name_link'),
                'path' => request('path_link'),
            ];
        } else {
            $path = $request->file('path')->store('projectfiles');

            $data = [
                'project_id' => request('project_id'),
                'type' => 'file',
                'name' => request('name'),
                'path' => $path,
            ];
        }

        $this->model::create($data);
    }

    public function getProjectName($id)
    {
        return Project::select('project_name')->where('id', $id)->first();
    }

    public function deleteProjectDocuments($id)
    {
        $data = $this->model::find($id);

        if ($data->type == 'file') {
            $getimagepath = $this->model::select('path')->where('id', $id)->first();
            $this->deleteStorage($getimagepath->path);
        }

        $this->model::find($id)->delete();

        return $data;
    }
}
