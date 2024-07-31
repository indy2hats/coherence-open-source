<?php

namespace App\Services;

use App\Repository\RecruitmentRepository;
use Illuminate\Support\Carbon;

class RecruitmentService
{
    protected $recruitmentRepository;

    public function __construct(RecruitmentRepository $recruitmentRepository)
    {
        $this->recruitmentRepository = $recruitmentRepository;
    }

    public function getCandidates($pagination)
    {
        return $this->recruitmentRepository->getCandidates($pagination);
    }

    public function getMachineTest1()
    {
        return $this->recruitmentRepository->getMachineTest1();
    }

    public function getMachineTest2()
    {
        return $this->recruitmentRepository->getMachineTest2();
    }

    public function getTechnicalInterview()
    {
        return $this->recruitmentRepository->getTechnicalInterview();
    }

    public function getHrInterview()
    {
        return $this->recruitmentRepository->getHrInterview();
    }

    public function getCurrentDate()
    {
        return date('d/m/Y');
    }

    public function createRecruitmentAndSchedule()
    {
        return $this->recruitmentRepository->createRecruitmentAndSchedule();
    }

    public function getRecruitmentWithSchedule($id)
    {
        return $this->recruitmentRepository->getRecruitmentWithSchedule($id);
    }

    public function updateRecruitment($id)
    {
        return $this->recruitmentRepository->updateRecruitment($id);
    }

    public function deleteScheduleAndRecruitment($id)
    {
        return $this->recruitmentRepository->deleteScheduleAndRecruitment($id);
    }

    public function getCandidatesForSearchCandidate($pagination)
    {
        return $this->recruitmentRepository->getCandidatesForSearchCandidate($pagination);
    }

    public function getMachineTest1WhereDate($date)
    {
        return $this->recruitmentRepository->getMachineTest1WhereDate($date);
    }

    public function getMachineTest2WhereDate($date)
    {
        return $this->recruitmentRepository->getMachineTest2WhereDate($date);
    }

    public function getTechnicalInterviewWhereDate($date)
    {
        return $this->recruitmentRepository->getTechnicalInterviewWhereDate($date);
    }

    public function getHrInterviewWhereDate($date)
    {
        return $this->recruitmentRepository->getHrInterviewWhereDate($date);
    }

    public function getDateYMD()
    {
        return Carbon::createFromFormat('d/m/Y', request('date'))->format('Y-m-d');
    }

    public function getDateDMY($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }

    public function getSchedule()
    {
        return $this->recruitmentRepository->getSchedule();
    }

    public function getMachineTest1ForUpdateSchedule()
    {
        return Carbon::createFromFormat('d/m/Y', request('machine_test1'))->format('Y-m-d').' '.date('H:i', strtotime(request('machine_test1_time')));
    }

    public function getMachineTest2ForUpdateSchedule()
    {
        return Carbon::createFromFormat('d/m/Y', request('machine_test2'))->format('Y-m-d').' '.date('H:i', strtotime(request('machine_test2_time')));
    }

    public function getTechnicalInterviewForUpdateSchedule()
    {
        return  Carbon::createFromFormat('d/m/Y', request('technical_interview'))->format('Y-m-d').' '.date('H:i', strtotime(request('technical_interview_time')));
    }

    public function getHrInterviewForUpdateSchedule()
    {
        return  Carbon::createFromFormat('d/m/Y', request('hr_interview'))->format('Y-m-d').' '.date('H:i', strtotime(request('hr_interview_time')));
    }

    public function updateSchedule()
    {
        $data = [
            'machine_test1_status' => request('machine_test1_status'),
            'machine_test2_status' => request('machine_test2_status'),
            'technical_interview_status' => request('technical_interview_status'),
            'hr_interview_status' => request('hr_interview_status'),
        ];

        if (request('machine_test1')) {
            $data += ['machine_test1' => $this->getMachineTest1ForUpdateSchedule()];
        } else {
            $data += ['machine_test1' => null];
        }

        if (request('machine_test2')) {
            $data += ['machine_test2' => $this->getMachineTest2ForUpdateSchedule()];
        } else {
            $data += ['machine_test2' => null];
        }

        if (request('technical_interview')) {
            $data += ['technical_interview' => $this->getTechnicalInterviewForUpdateSchedule()];
        } else {
            $data += ['technical_interview' => null];
        }

        if (request('hr_interview')) {
            $data += ['hr_interview' => $this->getHrInterviewForUpdateSchedule()];
        } else {
            $data += ['hr_interview' => null];
        }

        $this->recruitmentRepository->updateSchedule($data);
    }

    public function getCandidatesForUpdateSchedule($pagination)
    {
        return $this->recruitmentRepository->getCandidatesForUpdateSchedule($pagination);
    }
}
