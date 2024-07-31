<?php

namespace App\Services;

use App\Repository\TeamRepository;

class TeamService
{
    protected $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function getTeamOfUser($userId)
    {
        return $this->teamRepository->getTeamOfUser($userId);
    }

    public function addToTeam($reportingTo, $reportees)
    {
        return $this->teamRepository->addToTeam($reportingTo, $reportees);
    }
}
