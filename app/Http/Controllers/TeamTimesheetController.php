<?php

namespace App\Http\Controllers;

use App\Services\TeamService;
use App\Services\TeamTimesheetService;
use App\Services\UserService;
use Illuminate\Http\Request;

class TeamTimesheetController extends Controller
{
    protected $teamService;
    protected $teamTimesheetService;
    protected $userService;

    public function __construct(
        TeamService $teamService,
        TeamTimesheetService $teamTimesheetService,
        UserService $userService
    ) {
        $this->teamService = $teamService;
        $this->teamTimesheetService = $teamTimesheetService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authUser = auth()->user();
        $userId = $authUser->id;
        $users = $this->userService->getAllActiveEmployees($userId)->pluck('full_name', 'id');
        unset($users[$userId]);

        $reportees = $this->teamService->getTeamOfUser($userId);
        $reporteesId = $reportees->pluck('id')->toArray();

        $teamTimesheet = $this->teamTimesheetService->getTeamTimesheet($userId);

        return view('timesheets.team.index', compact('teamTimesheet', 'authUser', 'users', 'reportees', 'reporteesId'));
    }

    public function searchTeamTimesheet(Request $request)
    {
        $authUser = auth()->user();
        $userId = $authUser->id;
        $users = $this->userService->getAllActiveEmployees($userId)->pluck('full_name', 'id');
        unset($users[$userId]);

        $reportees = $this->teamService->getTeamOfUser($userId);
        $reporteesId = $reportees->pluck('id')->toArray();

        $filter = $request['filter'];
        $teamTimesheet = $this->teamTimesheetService->getTeamTimesheet($userId, $filter);

        return view('timesheets.team.index', compact('teamTimesheet', 'authUser', 'users', 'reportees', 'reporteesId', 'filter'));
    }
}
