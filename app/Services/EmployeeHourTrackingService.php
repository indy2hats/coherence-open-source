<?php

namespace App\Services;

use App\Models\Holiday;
use App\Models\Leave;
use App\Models\User;

class EmployeeHourTrackingService
{
    public function getWorkDay($checkDate)
    {
        if ($checkDate->dayOfWeek == 6 || $checkDate->dayOfWeek == 0) {
            $checkDate = $checkDate->subDays(2);
        }

        if ($this->checkHolidays($checkDate)) {
            $this->getWorkDay($checkDate->subDays(1));
        }

        return $checkDate;
    }

    public function checkHolidays($checkDate)
    {
        $holidays = Holiday::pluck('holiday_date')->toArray();

        return (array_search($checkDate->format('Y-m-d'), $holidays) !== false) ? true : false;
    }

    public function userOnWork($checkDate, $userLeaves)
    {
        if ($userLeaves->isEmpty()) {
            return 1;
        }

        $userLeaves = $userLeaves->whereIn('status', ['Approved', 'Waiting']);

        $userLeavesRange = $userLeaves->pluck('from_date', 'to_date')->toArray();
        foreach ($userLeavesRange as $fromDate => $toDate) {
            if ($checkDate->between($fromDate, $toDate)) {
                return false;
            }
        }

        $userLeavesType = $userLeaves->where('from_date', '<=', $checkDate->format('Y-m-d'))
            ->where('to_date', '>=', $checkDate->format('Y-m-d'))->pluck('session')->toArray();

        if (empty($userLeavesType)) {
            return 1;
        }
        if (in_array('Full Day', $userLeavesType)) {
            return false;
        }
        if (in_array('First Half', $userLeavesType) || in_array('Second Half', $userLeavesType)) {
            return 2;
        }

        return 1;
    }

    public function addLeaveToUser($userId, $leaveCount, $leaveDate)
    {
        $data = config('general.leaves.automated-leaves');
        $data['user_id'] = $userId;
        $data['from_date'] = $leaveDate;
        $data['to_date'] = $leaveDate;
        $hrManager = User::hrManagers()->first();
        $data['approved_by'] = $hrManager->id ?? null;
        if ($leaveCount == 1) {
            $data['session'] = 'Full Day';
            Leave::create($data);
        } else {
            $leave = Leave::where('user_id', $userId)
                ->whereDate('from_date', $leaveDate)->first();
            $leaveType = $leave->session ?? null;
            switch ($leaveType) {
                case 'First Half':
                    $type = 'Second Half';
                    break;
                case 'Second Half':
                    $type = 'First Half';
                    break;
                default:
                    $type = 'Full Day';
                    break;
            }
            $data['session'] = $type;
            Leave::create($data);
        }
    }
}
