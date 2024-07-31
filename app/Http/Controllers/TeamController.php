<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\TeamService;
use App\Services\TeamTimesheetService;
use App\Services\UserService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    protected $teamTimesheetService;
    protected $teamService;
    protected UserService $userService;

    public function __construct(TeamService $teamService, TeamTimesheetService $teamTimesheetService, UserService $userService)
    {
        $this->teamService = $teamService;
        $this->teamTimesheetService = $teamTimesheetService;
        $this->userService = $userService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'reviewer' => 'required',
            'reportees' => 'required'
        ]);

        $this->teamService->addToTeam($request->reviewer, $request->reportees);

        $authUser = auth()->user();
        $userId = $authUser->id;
        $users = $this->userService->getAllActiveEmployees($userId)->pluck('full_name', 'id');
        unset($users[$userId]);

        $reportees = $this->teamService->getTeamOfUser($userId);
        $reporteesId = $reportees->pluck('id')->toArray();

        $teamTimesheet = $this->teamTimesheetService->getTeamTimesheet($userId);
        $content = view('timesheets.team.managesheet', compact('teamTimesheet', 'authUser', 'users', 'reportees', 'reporteesId'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Team member added successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($team)
    {
        $team = Team::find($team);
        $removedUser = $team->reportee;
        $team->delete();

        $authUser = auth()->user();
        $userId = $authUser->id;
        $users = $this->userService->getAllActiveEmployees($userId)->pluck('full_name', 'id');
        unset($users[$userId]);

        $reportees = $this->teamService->getTeamOfUser($userId);
        $reporteesId = $reportees->pluck('id')->toArray();

        $teamTimesheet = $this->teamTimesheetService->getTeamTimesheet($userId);
        $content = view('timesheets.team.managesheet', compact('teamTimesheet', 'authUser', 'users', 'reportees', 'reporteesId'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Team member removed successfully',
            'data' => $content,
            'user' => $removedUser
        ];

        return response()->json($res);
    }
}
