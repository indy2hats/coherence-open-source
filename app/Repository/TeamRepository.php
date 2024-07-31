<?php

namespace App\Repository;

use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\Log;

class TeamRepository
{
    protected $model;

    public function __construct(Team $team)
    {
        $this->model = $team;
    }

    public function getTeamOfUser($userId)
    {
        $reportees = $this->model::where('reporting_to', $userId)
                            ->with('reportee_user:id,first_name,last_name')
                            ->get()
                            ->pluck('reportee_user', 'id');

        return $reportees;
    }

    public function addToTeam($reportingTo, $reportees)
    {
        try {
            $this->model::where('reporting_to', $reportingTo)->delete();
            foreach ($reportees as $reportee) {
                $this->model::create([
                    'reporting_to' => $reportingTo,
                    'reportee' => $reportee,
                ]);
            }
        } catch (Exception $e) {
            Log::info('Failed to add team '.$e);
        }
    }
}
