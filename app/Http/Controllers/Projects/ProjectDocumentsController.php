<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Services\ProjectDocumentService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ProjectDocumentsController extends Controller
{
    use GeneralTrait;

    protected $projectDocumentService;

    public function __construct(ProjectDocumentService $projectDocumentService)
    {
        $this->projectDocumentService = $projectDocumentService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (request('type') == 'link') {
            $request->validate([
                'name_link' => 'required',
                'path_link' => 'required',
            ]);
        } else {
            $request->validate([
                'name' => 'required',
                'path' => 'required',
            ]);
        }

        $this->projectDocumentService->createProjectDocuments($request);

        $files = $this->projectDocumentService->ajaxListProjectDocuments(request('project_id'));
        $content = view('projects.project_files.files-list', compact('files'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Document Uploaded Successfully',
            'data' => $content,
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
        $files = $this->projectDocumentService->ajaxListProjectDocuments($id);
        $project = $this->projectDocumentService->getProjectName($id);

        return view('projects.project_files.index', compact('id', 'project', 'files'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->projectDocumentService->deleteProjectDocuments($id);
        $files = $this->projectDocumentService->ajaxListProjectDocuments($data->project_id);
        $content = view('projects.project_files.files-list', compact('files'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Document Deleted Successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }
}
