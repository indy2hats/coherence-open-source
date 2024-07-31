<?php

namespace App\Http\Controllers\Checklists;

use App\Http\Controllers\Controller;
use App\Services\CheckListService;
use App\Traits\GeneralTrait;

class ChecklistController extends Controller
{
    use GeneralTrait;

    protected $checkListService;

    public function __construct(CheckListService $checkListService)
    {
        $this->checkListService = $checkListService;
    }

    public function useChecklists()
    {
        $myChecklists = $this->checkListService->getMyChecklists();
        $updatedList = $this->checkListService->checkListUpdate();

        return view('checklists.checklist.index', compact('myChecklists', 'updatedList'));
    }

    public function updateUserChecklist()
    {
        request()->validate([
            'checklists' => 'required'
        ]);

        $lists = $this->checkListService->updateUserChecklist();

        $myChecklists = $lists['myChecklists'];
        $updatedList = $lists['updatedList'];
        $content = view('checklists.checklist.view', compact('myChecklists', 'updatedList'))->render();

        $res = [
            'status' => 'success',
            'data' => $content,
            'message' => 'List Updated successfully'
        ];

        return response()->json($res);
    }

    public function saveChecklist()
    {
        request()->validate([
            'datepicker' => 'required',
            'save_id' => 'required'
        ]);

        $lists = $this->checkListService->saveChecklist();
        $myChecklists = $lists['myChecklists'];
        $updatedList = $lists['updatedList'];

        $content = view('checklists.checklist.view', compact('myChecklists', 'updatedList'))->render();

        $res = [
            'status' => 'success',
            'data' => $content
        ];

        return response()->json($res);
    }
}
