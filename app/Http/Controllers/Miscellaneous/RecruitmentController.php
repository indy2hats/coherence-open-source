<?php

namespace App\Http\Controllers\Miscellaneous;

use App\Http\Controllers\Controller;
use App\Services\RecruitmentService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    use GeneralTrait;

    public $pagination;
    private $recruitmentService;

    public function __construct(RecruitmentService $recruitmentService)
    {
        $this->pagination = config('general.recruitments.pagination');
        $this->recruitmentService = $recruitmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $names = $this->getRecruitmentNames();
        $candidates = $this->recruitmentService->getCandidates($this->pagination);
        $machine_test1 = $this->recruitmentService->getMachineTest1();
        $machine_test2 = $this->recruitmentService->getMachineTest2();
        $technical_interview = $this->recruitmentService->getTechnicalInterview();
        $hr_interview = $this->recruitmentService->getHrInterview();
        $date = $this->recruitmentService->getCurrentDate();

        return view('recruitments.index', compact('names', 'candidates', 'machine_test1', 'machine_test2', 'technical_interview', 'hr_interview', 'date'));
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
            'name' => 'required',
            'email' => 'required|unique:recruitments,email',
            'phone' => 'required',
            'resume' => 'required',
            'category' => 'required',
            'source' => 'required',
        ]);

        $this->recruitmentService->createRecruitmentAndSchedule();

        $names = $this->getRecruitmentNames();
        $candidates = $this->recruitmentService->getCandidates($this->pagination);
        $machine_test1 = $this->recruitmentService->getMachineTest1();
        $machine_test2 = $this->recruitmentService->getMachineTest2();
        $technical_interview = $this->recruitmentService->getTechnicalInterview();
        $hr_interview = $this->recruitmentService->getHrInterview();
        $date = $this->recruitmentService->getCurrentDate();

        $content = view('recruitments.view', compact('names', 'candidates', 'machine_test1', 'machine_test2', 'technical_interview', 'hr_interview', 'date'))->render();

        $res = [
            'status' => 'Ok',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidate = $this->recruitmentService->getRecruitmentWithSchedule($id);

        return view('recruitments.details.index', compact('candidate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidate = $this->getRecruitmentById($id);

        return view('recruitments.edit', compact('candidate'));
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
            'name' => 'required',
            'email' => 'required|unique:recruitments,email,'.$id,
            'phone' => 'required',
            'source' => 'required',
            'category' => 'required'
        ]);

        $this->recruitmentService->updateRecruitment($id);

        $names = $this->getRecruitmentNames();
        $candidates = $this->recruitmentService->getCandidates($this->pagination);
        $machine_test1 = $this->recruitmentService->getMachineTest1();
        $machine_test2 = $this->recruitmentService->getMachineTest2();
        $technical_interview = $this->recruitmentService->getTechnicalInterview();
        $hr_interview = $this->recruitmentService->getHrInterview();
        $date = $this->recruitmentService->getCurrentDate();

        $content = view('recruitments.view', compact('names', 'candidates', 'machine_test1', 'machine_test2', 'technical_interview', 'hr_interview', 'date'))->render();
        $res = [
            'status' => 'Ok',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->recruitmentService->deleteScheduleAndRecruitment($id);

        $names = $this->getRecruitmentNames();
        $candidates = $this->recruitmentService->getCandidates($this->pagination);
        $machine_test1 = $this->recruitmentService->getMachineTest1();
        $machine_test2 = $this->recruitmentService->getMachineTest2();
        $technical_interview = $this->recruitmentService->getTechnicalInterview();
        $hr_interview = $this->recruitmentService->getHrInterview();
        $date = $this->recruitmentService->getCurrentDate();

        $content = view('recruitments.view', compact('names', 'candidates', 'machine_test1', 'machine_test2', 'technical_interview', 'hr_interview', 'date'))->render();

        $res = [
            'status' => 'Ok',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function searchCandidate(Request $request)
    {
        $candidates = $this->recruitmentService->getCandidatesForSearchCandidate($this->pagination);
        $names = $this->getRecruitmentNames();
        $machine_test1 = $this->recruitmentService->getMachineTest1();
        $machine_test2 = $this->recruitmentService->getMachineTest2();
        $technical_interview = $this->recruitmentService->getTechnicalInterview();
        $hr_interview = $this->recruitmentService->getHrInterview();
        $date = request('daterange');

        $content = view('recruitments.list', compact('names', 'candidates', 'machine_test1', 'machine_test2', 'technical_interview', 'hr_interview', 'date'))->render();

        $res = [
            'status' => 'Ok',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Retrieves the schedule from the recruitment service and returns it as a JSON response.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the schedule.
     */
    public function getSchedule()
    {
        $schedule = $this->recruitmentService->getSchedule();

        $content = view('recruitments.new-schedule', compact('schedule'))->render();
        $res = [
            'status' => 'Ok',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Updates the recruitment schedule based on the provided request data.
     *
     * @param  Request  $request  The request object containing the updated schedule data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the updated schedule data.
     */
    public function updateSchedule(Request $request)
    {
        $request->validate([
            'machine_test1' => 'required_if:machine_test1_status,==,Scheduled',
            'machine_test2' => 'required_if:machine_test2_status,==,Scheduled',
            'technical_interview' => 'required_if:technical_interview_status,==,Scheduled',
            'hr_interview' => 'required_if:hr_interview_status,==,Scheduled',
            'machine_test1_time' => 'required_if:machine_test1_status,==,Scheduled',
            'machine_test2_time' => 'required_if:machine_test2_status,==,Scheduled',
            'technical_interview_time' => 'required_if:technical_interview_status,==,Scheduled',
            'hr_interview_time' => 'required_if:hr_interview_status,==,Scheduled',
        ]);

        $this->recruitmentService->updateSchedule();

        $names = $this->getRecruitmentNames();
        $candidates = $this->recruitmentService->getCandidatesForUpdateSchedule($this->pagination);
        $machine_test1 = $this->recruitmentService->getMachineTest1();
        $machine_test2 = $this->recruitmentService->getMachineTest2();
        $technical_interview = $this->recruitmentService->getTechnicalInterview();
        $hr_interview = $this->recruitmentService->getHrInterview();
        $date = $this->recruitmentService->getCurrentDate();

        $content = view('recruitments.view', compact('names', 'candidates', 'machine_test1', 'machine_test2', 'technical_interview', 'hr_interview', 'date'))->render();
        $res = [
            'status' => 'Ok',
            'data' => $content,
        ];

        return response()->json($res);
    }
}
