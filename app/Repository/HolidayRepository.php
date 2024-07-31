<?php

namespace App\Repository;

use App\Models\Holiday;
use Carbon\Carbon;
use DB;

class HolidayRepository
{
    protected $model;

    public function __construct(Holiday $holiday)
    {
        $this->model = $holiday;
    }

    public function getHolidayLists($date)
    {
        return DB::select('select * from holidays where YEAR(holiday_date) = ? order by holiday_date ASC', [$date]);
    }

    public function createHoliday()
    {
        $data = [
            'holiday_date' => $this->getHolidayDate(),
            'holiday_name' => request('holiday_name'),
        ];

        $this->model::create($data);
    }

    public function getHolidayDate()
    {
        return Carbon::createFromFormat('d/m/Y', request('holiday_date'))->format('Y-m-d');
    }

    public function updateHoliday($id)
    {
        $data = [
            'holiday_date' => $this->getHolidayDate(),
            'holiday_name' => request('holiday_name'),
        ];

        $this->model::find($id)->update($data);
    }
}
