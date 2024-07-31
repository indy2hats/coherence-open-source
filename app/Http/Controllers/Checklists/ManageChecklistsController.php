<?php

namespace App\Http\Controllers\Checklists;

use App\Http\Controllers\Controller;
use App\Services\CheckListService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ManageChecklistsController extends Controller
{
    use GeneralTrait;

    protected $checkListService;

    public function __construct(CheckListService $checkListService)
    {
        $this->checkListService = $checkListService;
    }

    public function index()
    {
        $myChecklists = $this->checkListService->getMyChecklists();
        $users = $this->getActiveUsers();

        return view('checklists.manage-checklist.index', compact('myChecklists', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $this->checkListService->createTaxonomyList();
        $myChecklists = $this->checkListService->getMyChecklists();

        $content = view('checklists.manage-checklist.view', compact('myChecklists'))->render();
        $res = [
            'status' => 'success',
            'data' => $content
        ];

        return response()->json($res);
    }

    public function destroy($id)
    {
        $this->deleteTaxonomyList($id);
        $myChecklists = $this->checkListService->getMyChecklists();

        $content = view('checklists.manage-checklist.view', compact('myChecklists'))->render();
        $res = [
            'status' => 'success',
            'data' => $content
        ];

        return response()->json($res);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'edit_title' => 'required',
        ]);

        $this->checkListService->updateTaxonomyList($id);
        $myChecklists = $this->checkListService->getMyChecklists();

        $content = view('checklists.manage-checklist.view', compact('myChecklists'))->render();

        $res = [
            'status' => 'success',
            'data' => $content
        ];

        return response()->json($res);
    }

    public function employeeChecklist()
    {
        $users = $this->getNotClientsActiveUsers();
        $list = [];

        return view('checklists.manage-checklist.employee-checklist', compact('users', 'list'));
    }

    public function shareChecklist()
    {
        request()->validate([
            'users' => 'required'
        ]);
        $this->checkListService->shareChecklist();

        return response()->json(['success' => 'OK']);
    }

    public function searchChecklist()
    {
        $list = $this->checkListService->getCheckListReport();

        $content = view('checklists.manage-checklist.employee-list', compact('list'))->render();
        $res = [
            'status' => 'success',
            'data' => $content
        ];

        return response()->json($res);
    }
}
