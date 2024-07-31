<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\ToolService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectCredentialsController extends Controller
{
    use GeneralTrait;

    private $toolService;

    public function __construct(ToolService $toolService)
    {
        $this->toolService = $toolService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'value' => 'required',
        ]);

        $items = $this->toolService->createProjectCredentials($request);

        $content = view('projects.project_credentials.list', compact('items'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Credentials Added Successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function show($id)
    {
        $items = $this->getProjectCredentialsByProjectId($id);
        $project = $this->getProject($id);

        if (! $project) {
            abort(404);
        }
        if (Gate::forUser($this->getCurrentUser())->denies('client-project-view', $project)) {
            abort(403);
        }

        return view('projects.project_credentials.index', compact('id', 'project', 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->getProjectCredentials($id);
        $this->deleteProjectCredentials($id);
        $items = $this->getProjectCredentialsByProjectId($data->project_id);

        $content = view('projects.project_credentials.list', compact('items'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Credentials Deleted Successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->getProjectCredentials($id);

        return view('projects.project_credentials.edit', compact('item'));
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
        $request->validate([
            'type' => 'required',
            'value' => 'required',
        ]);

        $items = $this->toolService->updateProjectCredentials($request, $id);

        $content = view('projects.project_credentials.list', compact('items'))->render();

        $res = [
            'status' => 'Saved',
            'message' => 'Credentials Updated Successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }
}
