<?php

namespace App\Services;

use App\Models\Holiday;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DayService
{
    public static function getNthLastWorkingday($n)
    {
        $checkDate = Carbon::now()->format('Y-m-d 00:00:00');
        $checkDate = Carbon::parse($checkDate);
        for ($i = 0; $i < $n; $i++) {
            $checkDate = self::getWorkDay($checkDate->subDays(1));
        }

        return  $checkDate;
    }

    public static function getWorkDay($checkDate)
    {
        if ($checkDate->dayOfWeek == 6 || $checkDate->dayOfWeek == 0) {
            $checkDate = $checkDate->subDays(1);
            $checkDate = self::getWorkDay($checkDate);
        }

        $holidays = Holiday::pluck('holiday_date')->toArray();
        $checkDate = (array_search($checkDate->format('Y-m-d'), $holidays) !== false) ? self::getWorkDay($checkDate->subDays(1)) : $checkDate;

        if (Auth::user()) {
            $checkDate = self::checkUserIsOnWork($checkDate);
        }

        return $checkDate;
    }

    public static function checkUserIsOnWork($checkDate)
    {
        $userLeavesRange = Leave::where('user_id', Auth::user()->id)->where(function ($query) use ($checkDate) {
            $query->whereDate('from_date', '=', $checkDate);
            $query->orWhereDate('to_date', '=', $checkDate);
        })->pluck('from_date')->toArray();

        if (! empty($userLeavesRange)) {
            return self::getWorkDay($checkDate->subDays(1));
        }

        $userLeaves = Leave::where('user_id', Auth::user()->id)->where('from_date', '>=', Carbon::now()->subDays(15)->format('Y-m-d'));

        $userLeavesRange = $userLeaves->where('from_date', '>=', Carbon::now()->subDays(15)->format('Y-m-d'))
            ->where('to_date', '<=', Carbon::now()->addDays(15)->format('Y-m-d'))
            ->pluck('to_date', 'from_date')->toArray();

        if (empty($userLeavesRange)) {
            return $checkDate;
        }

        foreach ($userLeavesRange as $fromDate => $toDate) {
            $checkDate = Carbon::parse($checkDate);
            $fromDate = Carbon::parse($fromDate)->format('Y-m-d 00:00:00');
            $toDate = Carbon::parse($toDate)->format('Y-m-d 00:00:00');
            if ($checkDate->gte($fromDate) && $checkDate->lte($toDate)) {
                return self::getWorkDay($checkDate->subDays(1));
            }
        }

        return $checkDate;
    }
}
