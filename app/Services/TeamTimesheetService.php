<?php

namespace App\Services;

use App\Repository\TeamRepository;
use App\Repository\TeamTimesheetRepository;

class TeamTimesheetService
{
    protected $teamRepository;
    protected $teamTimesheetRepository;

    public function __construct(TeamRepository $teamRepository, TeamTimesheetRepository $teamTimesheetRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->teamTimesheetRepository = $teamTimesheetRepository;
    }

    public function getTeamTimesheet($userId, $filter = null)
    {
        return $this->teamTimesheetRepository->getTeamTimesheet($userId, $filter);
    }
}
