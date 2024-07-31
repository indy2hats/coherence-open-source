<?php

namespace App\Http\Controllers\Miscellaneous;

use App\Http\Controllers\Controller;
use App\Services\RecruitmentService;
use App\Traits\GeneralTrait;

class RecruitmentSchedulesController extends Controller
{
    use GeneralTrait;

    public $pagination;
    private $recruitmentService;

    public function __construct(RecruitmentService $recruitmentService)
    {
        $this->recruitmentService = $recruitmentService;
    }

    /**
     * Retrieves the schedules for machine tests, technical interviews, and HR interviews,
     * along with the current date, and returns a view displaying the schedules.
     *
     * @return \Illuminate\Contracts\View\View The view displaying the schedules.
     */
    public function listSchedules()
    {
        $machine_test1 = $this->recruitmentService->getMachineTest1();
        $machine_test2 = $this->recruitmentService->getMachineTest2();
        $technical_interview = $this->recruitmentService->getTechnicalInterview();
        $hr_interview = $this->recruitmentService->getHrInterview();
        $date = $this->recruitmentService->getCurrentDate();

        return view('recruitments.schedules', compact('machine_test1', 'machine_test2', 'technical_interview', 'hr_interview', 'date'));
    }

    /**
     * Retrieves the schedules for machine tests, technical interviews, and HR interviews,
     * along with the current date, and returns a JSON response containing the rendered view displaying the schedules.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the rendered view displaying the schedules.
     */
    public function searchSchedule()
    {
        $date = $this->recruitmentService->getDateYMD();
        $machine_test1 = $this->recruitmentService->getMachineTest1WhereDate($date);
        $machine_test2 = $this->recruitmentService->getMachineTest2WhereDate($date);
        $technical_interview = $this->recruitmentService->getTechnicalInterviewWhereDate($date);
        $hr_interview = $this->recruitmentService->getHrInterviewWhereDate($date);
        $date = $this->recruitmentService->getDateDMY($date);

        $content = view('recruitments.schedule', compact('machine_test1', 'machine_test2', 'technical_interview', 'hr_interview', 'date'))->render();
        $res = [
            'status' => 'Ok',
            'data' => $content,
        ];

        return response()->json($res);
    }
}
