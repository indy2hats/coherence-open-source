<?php

namespace App\Http\Controllers\Checklists;

use App\Http\Controllers\Controller;
use App\Services\CheckListService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    use GeneralTrait;

    protected $checkListService;

    public function __construct(CheckListService $checkListService)
    {
        $this->checkListService = $checkListService;
    }

    public function checklistReport()
    {
        $date = date('d/m/Y');
        $users = $this->checkListService->getUsersNotClients();
        $checklists = $this->checkListService->getChecklists();

        return view('checklists.report.index', compact('date', 'users', 'checklists'));
    }

    public function searchChecklistReport(Request $request)
    {
        $date = $this->checkListService->getDate($request);
        $formattedDate = date('M d D, Y', strtotime($date));
        $data = $this->checkListService->getDataForSearchChecklistReport($date);

        $content = view('checklists.report.view', compact('data'))->render();
        $res = [
            'data' => $content,
            'status' => 'success',
            'formatted_date' => $formattedDate
        ];

        return response()->json($res);
    }
}
