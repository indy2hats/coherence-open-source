<?php

namespace App\Http\Controllers\Miscellaneous;

use App\Http\Controllers\Controller;
use App\Services\HolidayService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    use GeneralTrait;

    private $holidayService;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = $this->getYear();
        $lists = [];
        $data = $this->getWeekHolidays();
        $days = [];

        foreach ($data as $value) {
            array_push($days, $value->day);
        }

        return view('settings.manageholiday.index', compact('lists', 'date', 'days'));
    }

    /**
     *ajax function for getting holiday list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHolidayList(Request $request)
    {
        $date = $request->holiday_date;
        $lists = $this->holidayService->getHolidayLists($date);
        $content = view('settings.manageholiday.list', compact('lists'))->render();

        $actions = view('settings.manageholiday.actions', compact('date'))->render();

        $res = [
            'status' => 'true',
            'data' => $content,
            'actions' => $actions
        ];

        return response()->json($res);
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
            'holiday_date' => 'required|date_format:d/m/Y',
            'holiday_name' => 'required',
        ]);

        $this->holidayService->createHoliday();

        return response()->json(['message' => 'Holdiay Added successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $holiday = $this->getHolidayById($id);

        return view('settings.manageholiday.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'holiday_date' => 'required|date_format:d/m/Y',
            'holiday_name' => 'required',
        ]);

        $this->holidayService->updateHoliday($id);

        return response()->json(['message' => 'Holdiay Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->deleteHoliday($id);

        return response()->json(['message' => 'Holiday Deleted successfully']);
    }

    public function manageWeeklyHolidays()
    {
        $this->holidayService->manageWeeklyHolidays();
    }

    public function exportHolidays(Request $request, int $year)
    {
        $request->validate(['year' => 'numeric|regex:/^\d{4}$/']);

        return $this->holidayService->exportHolidays($year);
    }
}
