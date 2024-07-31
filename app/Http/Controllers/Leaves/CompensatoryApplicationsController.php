<?php

namespace App\Http\Controllers\Leaves;

use App\Http\Controllers\Controller;
use App\Services\CompensatoryService;
use App\Traits\GeneralTrait;

class CompensatoryApplicationsController extends Controller
{
    use GeneralTrait;

    private $compensatoryService;

    public function __construct(CompensatoryService $compensatoryService)
    {
        $this->compensatoryService = $compensatoryService;
    }

    public function compensatoryApplications()
    {
        $year = $this->getYear();
        $users = $this->compensatoryService->getUsers();
        $list = $this->compensatoryService->getPendingList();
        $previous = $this->compensatoryService->getPrevious($year);

        return view('compensatory.admin.index', compact('list', 'previous', 'year', 'users'));
    }

    public function acceptApplication()
    {
        $this->compensatoryService->acceptApplication(request('ApplicationId'));

        $year = $this->getYear();
        $users = $this->compensatoryService->getUsers();
        $list = $this->compensatoryService->getPendingList();
        $previous = $this->compensatoryService->getPreviousForAcceptRejectApplication();

        $content = view('compensatory.admin.list', compact('list', 'previous', 'year', 'users'))->render();
        $res = [
            'message' => 'Application approved succesfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function rejectApplication()
    {
        request()->validate([
            'reason' => 'required',
        ]);

        $this->compensatoryService->rejectApplication();

        $year = $this->getYear();
        $users = $this->compensatoryService->getUsers();
        $list = $this->compensatoryService->getPendingList();
        $previous = $this->compensatoryService->getPreviousForAcceptRejectApplication();

        $content = view('compensatory.admin.list', compact('list', 'previous', 'year', 'users'))->render();
        $res = [
            'message' => 'Application rejected succesfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function applicationSearch()
    {
        $previous = $this->compensatoryService->getPreviousForApplicationSearch();

        $content = view('compensatory.admin.previous', compact('previous'))->render();
        $res = [
            'status' => 'Ok',
            'data' => $content,
        ];

        return response()->json($res);
    }
}
