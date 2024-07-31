<?php

namespace App\Services;

use App\Models\WeekHoliday;
use App\Repository\HolidayRepository;
use App\Traits\GeneralTrait;
use PDF;

class HolidayService
{
    use GeneralTrait;

    protected $holidayRepository;

    public function __construct(HolidayRepository $holidayRepository)
    {
        $this->holidayRepository = $holidayRepository;
    }

    public function getHolidayLists($year)
    {
        return $this->holidayRepository->getHolidayLists($year);
    }

    public function createHoliday()
    {
        return $this->holidayRepository->createHoliday();
    }

    public function updateHoliday($id)
    {
        return $this->holidayRepository->updateHoliday($id);
    }

    public function manageWeeklyHolidays()
    {
        WeekHoliday::truncate();

        if (request('days')) {
            foreach (request('days') as $value) {
                $data = [
                    'day' => $value,
                ];

                $this->createWeekHoliday($data);
            }
        }
    }

    public function exportHolidays($year)
    {
        $data = ['lists' => $this->getHolidayLists($year), 'year' => $year];

        $pdf = PDF::loadView('settings.manageholiday.export-pdf', $data);

        return $pdf->download('Holidays-'.$year.'.pdf');
    }
}
