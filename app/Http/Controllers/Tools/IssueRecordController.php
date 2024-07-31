<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\ToolService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class IssueRecordController extends Controller
{
    use GeneralTrait;

    private $toolService;

    public function __construct(ToolService $toolService)
    {
        $this->toolService = $toolService;
    }

    /** Used to fetch all details needed for clients.index */
    public function index()
    {
        $users = $this->toolService->getUserNotClients();
        $projects = $this->getProjectsOrderByProjectName();
        $categories = $this->toolService->getCategories();
        $fromDate = $this->toolService->getFromDate();
        $toDate = $this->toolService->getToDate();

        return view('issue-records.index', compact('projects', 'users', 'fromDate', 'toDate', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'category' => 'required',
            'title' => 'required',
            'description' => 'required',
            'solution' => 'required',
        ]);

        $this->toolService->createIssue($request);

        $res = [
            'status' => 'OK',
            'message' => 'Issue Added successfully',
        ];

        return response()->json($res);
    }

    public function show($id)
    {
        $issue = $this->findIssue($id);

        return view('issue-records.show', compact('issue'));
    }

    public function edit($id)
    {
        $issue = $this->findIssue($id);
        $projects = $this->getProjectsOrderByProjectName();
        $categories = $this->toolService->getCategories();

        return view('issue-records.edit', compact('issue', 'projects', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'project_id' => 'required',
            'category' => 'required',
            'title' => 'required',
            'description' => 'required',
            'solution' => 'required',
        ]);

        $this->toolService->updateIssue($request, $id);

        $res = [
            'status' => 'OK',
            'message' => 'Issue Updated successfully',
        ];

        return response()->json($res);
    }

    public function issueRecordsSearch(Request $request)
    {
        $issues = $this->toolService->getIssuesForIssueRecordSearch($request);

        $content = view('issue-records.sheet', compact('issues'))->render();

        $res = [
            'data' => $content,
            'status' => 'success'
        ];

        return response()->json($res);
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'title' => 'required | unique:issue_categories,title',
        ]);

        $category = $this->toolService->createCategory($request);

        $res = [
            'status' => 'OK',
            'message' => 'Issue Added successfully',
            'category' => $category
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
        $issueRecord = $this->deleteIssue($id);

        $res = [
            'status' => 'OK',
            'message' => 'Issue Deleted successfully',
        ];

        return response()->json($res);
    }
}
