<?php

namespace App\Services;

use App\Repository\ProjectCostRepository;
use App\Traits\GeneralTrait;
use Carbon\Carbon;

class ProjectCostService
{
    use GeneralTrait;

    public static function getHourlyRateForEmployee($employeeId, $createdAt)
    {
        $monthYear = self::getMonthYear($createdAt);
        $currentMonthYear = date('M-Y');

        if ($monthYear === $currentMonthYear) {
            $previousMonthYear = self::getPreviousMonthYear();
            $monthYear = $previousMonthYear;
        }

        $totalHours = self::getTotalHoursWorkedForAMonth($employeeId, $monthYear);
        if (empty($totalHours)) {
            return 0;
        }

        $data = EmployeePayslipService::getPayslipComponents($employeeId, $monthYear);

        if (empty($data['employeePayroll'])) {
            $data = EmployeePayslipService::getLatestPayslipComponents($employeeId, $monthYear);
        }

        return ($data['employeePayroll']->employee_ctc ?? 0) / $totalHours;
    }

    public static function getMonthYear($createdAt)
    {
        return Carbon::parse($createdAt)->format('M-Y');
    }

    public static function getPreviousMonthYear()
    {
        return date('M-Y', strtotime('-1 month'));
    }

    public static function getTotalHoursWorkedForAMonth($employeeId, $monthYear)
    {
        [$monthStr, $year] = explode('-', $monthYear);
        $timestamp = strtotime("$monthStr 1 $year");
        $month = date('m', $timestamp);
        $totalMinutes = ProjectCostRepository::getTotalMinutesWorkedForAMonth($employeeId, $year, $month);

        return $totalMinutes / 60;
    }

    public static function getStartDate($startDate)
    {
        return Carbon::createFromFormat('m/d/Y', trim($startDate))->startOfDay();
    }

    public static function getEndDate($endDate)
    {
        return Carbon::createFromFormat('m/d/Y', trim($endDate))->endOfDay();
    }

    public static function getProjectCostDetails($request)
    {
        $projectId = $request->input('projectId');
        $dateRange = $request->input('dateRange');
        $selectedUserId = $request->input('user');
        $sessionType = $request->input('session_type');

        $startDate = null;
        $endDate = null;
        if (! empty($dateRange)) {
            [$startDate, $endDate] = explode(' - ', $dateRange);
            $startDate = self::getStartDate($startDate);
            $endDate = self::getEndDate($endDate);
        }

        $project = ProjectCostRepository::getProjectCostDetails($projectId, $startDate, $endDate, $selectedUserId, $sessionType);

        $totalCostByEmployee = [];
        $sessionCostByEmployee = [];

        foreach ($project->task as $task) {
            foreach ($task->tasks_session as $session) {
                $userId = $session->user_id;
                $total = $session->total;

                if (! isset($totalCostByEmployee[$userId])) {
                    $totalCostByEmployee[$userId] = 0;
                }

                if (empty($session->total)) {
                    continue;
                }

                $hourlyRate = self::getHourlyRateForEmployee($userId, $session->created_at);
                $sessionCost = ($total / 60) * $hourlyRate;

                $totalCostByEmployee[$userId] += $sessionCost;
                $sessionCostByEmployee[$userId][] = number_format($hourlyRate, 2).'(hourly rate) * '.($total / 60).'(session in hrs)';
            }
        }

        $employeeCosts = [];
        $totalProjectCost = 0;

        foreach ($totalCostByEmployee as $userId => $cost) {
            if ($cost > 0) {
                $employee = self::getUserByid($userId);
                if ($employee) {
                    $employeeName = $employee->first_name.' '.$employee->last_name;
                    $employeeCosts[$employeeName] = number_format($cost, 2);
                    $totalProjectCost += $cost;
                }
            }
        }
        $sessionCost = [];
        foreach ($sessionCostByEmployee as $userId => $cost) {
            $employee = self::getUserByid($userId);
            if ($employee) {
                $employeeName = $employee->first_name.' '.$employee->last_name;
                $sessionCost[$employeeName] = $cost;
            }
        }

        $projectName = $project->project_name;
        $salaryCurrency = self::getSalaryCurrency();
        $selectedUser = null;
        if (! empty($selectedUserId)) {
            $selectedUser = self::getUserByid($selectedUserId)->full_name;
        }

        $sessionTypeTitle = self::getSessionTypeTitle($sessionType);

        $response = [
            'sessionCost' => $sessionCost,
            'employeeCosts' => $employeeCosts,
            'projectName' => $projectName,
            'totalProjectCost' => $totalProjectCost,
            'salaryCurrency' => $salaryCurrency,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'user' => $selectedUser,
            'sessionType' => $sessionTypeTitle
        ];

        return $response;
    }
}
