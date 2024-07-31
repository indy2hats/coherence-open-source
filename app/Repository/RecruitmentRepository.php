<?php

namespace App\Repository;

use App\Models\Recruitment;
use App\Models\Schedule;
use App\Traits\GeneralTrait;
use DateTime;
use Illuminate\Support\Carbon;

use function PHPUnit\Framework\fileExists;

class RecruitmentRepository
{
    use GeneralTrait;

    protected $model;

    public function __construct(Recruitment $recruitment)
    {
        $this->model = $recruitment;
    }

    public function getCandidates($pagination)
    {
        return $this->model::orderBy('applied_date', 'DESC')->where('created_at', 'like', Carbon::today()->toDateString().'%')->paginate($pagination);
    }

    public function getMachineTest1()
    {
        return Schedule::with('candidate')->where('machine_test1', 'like', '%'.date('Y-m-d').'%')->where('machine_test1_status', 'Scheduled')->get();
    }

    public function getMachineTest1WhereDate($date)
    {
        return Schedule::with('candidate')->whereDate('machine_test1', $date)->where('machine_test1_status', 'Scheduled')->get();
    }

    public function getMachineTest2()
    {
        return Schedule::with('candidate')->where('machine_test2', 'like', '%'.date('Y-m-d').'%')->where('machine_test2_status', 'Scheduled')->get();
    }

    public function getMachineTest2WhereDate($date)
    {
        return Schedule::with('candidate')->whereDate('machine_test2', $date)->where('machine_test2_status', 'Scheduled')->get();
    }

    public function getTechnicalInterview()
    {
        return Schedule::with('candidate')->where('technical_interview', 'like', '%'.date('Y-m-d').'%')->where('technical_interview_status', 'Scheduled')->get();
    }

    public function getTechnicalInterviewWhereDate($date)
    {
        return Schedule::with('candidate')->whereDate('technical_interview', $date)->where('technical_interview_status', 'Scheduled')->get();
    }

    public function getHrInterview()
    {
        return Schedule::with('candidate')->where('hr_interview', 'like', '%'.date('Y-m-d').'%')->where('hr_interview_status', 'Scheduled')->get();
    }

    public function getHrInterviewWhereDate($date)
    {
        return Schedule::with('candidate')->whereDate('hr_interview', $date)->where('hr_interview_status', 'Scheduled')->get();
    }

    public function createRecruitmentAndSchedule()
    {
        $careerStartDate = $this->getCareerStartDate();
        $appliedDate = $this->getAppliedDate();

        $data = [
            'name' => ucwords(strtolower(request('name'))),
            'email' => request('email'),
            'phone' => request('phone'),
            'category' => request('category'),
            'description' => request('description'),
            'source' => request('source'),
            'career_start_date' => $careerStartDate,
            'applied_date' => $appliedDate,
            'status' => request('status') ? request('status') : 'Pending',
        ];

        $file = request('resume');
        $data += ['resume' => $file->storeAs('resumes', request('email').'_'.$file->getClientOriginalName())];
        $id = $this->createRecruitment($data)->id;

        $this->createSchedule([
            'recruitment_id' => $id,
            'machine_test1_status' => 'Not Done',
            'machine_test2_status' => 'Not Done',
            'technical_interview_status' => ' Not Done',
            'hr_interview_status' => 'Not Done'
        ]);
    }

    public function updateRecruitment($id)
    {
        $careerStartDate = $this->getCareerStartDate();
        $appliedDate = $this->getAppliedDate();

        $data = [
            'name' => ucwords(strtolower(request('name'))),
            'email' => request('email'),
            'phone' => request('phone'),
            'category' => request('category'),
            'description' => request('description'),
            'status' => request('status'),
            'source' => request('source'),
            'career_start_date' => $careerStartDate,
            'applied_date' => $appliedDate,
        ];

        if (request('resume')) {
            $resumeFile = $this->model::find($id)->resume;
            if (fileExists('storage/'.$resumeFile)) {
                unlink('storage/'.$resumeFile);
            }
            $file = request('resume');

            $data += ['resume' => $file->storeAs('resumes', request('email').'_'.$file->getClientOriginalName())];
        }

        $this->model::find($id)->update($data);
    }

    public function deleteScheduleAndRecruitment($id)
    {
        Schedule::where('recruitment_id', $id)->delete();
        $resumeFile = $this->model::find($id)->resume;
        if (fileExists('storage/'.$resumeFile)) {
            unlink('storage/'.$resumeFile);
        }

        $this->model::find($id)->delete();
    }

    public function getCareerStartDate()
    {
        return request('career_start_date') ? Carbon::createFromFormat('d/m/Y', request('career_start_date'))->format('Y-m-d') : null;
    }

    public function getAppliedDate()
    {
        return request('applied_date') ? Carbon::createFromFormat('d/m/Y', request('applied_date'))->format('Y-m-d') : Carbon::now();
    }

    public function getRecruitmentWithSchedule($id)
    {
        return $this->model::with('schedule')->where('id', $id)->first();
    }

    public function getCandidatesForSearchCandidate($pagination)
    {
        $candidates = $this->model::where('id', '!=', null);

        $id = request('filter_name');

        $status = request('filter_status');

        $category = request('filter_category');

        $applied = request('filter_applied');

        $date = request('daterange');

        $candidates->when(! empty($id), function ($q) use ($id) {
            return $q->where('id', $id);
        });

        $candidates->when(! empty($status), function ($q) use ($status) {
            return $q->where('status', $status);
        });

        $candidates->when(! empty($category), function ($q) use ($category) {
            return $q->where('category', $category);
        });
        if (! empty(request('daterange'))) {
            $daterange = explode(' - ', $date);
            $startDate = Carbon::parse($daterange[0])->startOfDay()->toDateTimeString();
            $endDate = Carbon::parse($daterange[1])->endOfDay()->toDateTimeString();
            $candidates = $candidates->where(function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', $startDate)
                      ->whereDate('created_at', '<=', $endDate);
            });
        } else {
            $candidates = $candidates->where('created_at', 'like', Carbon::today()->toDateString().'%');
        }

        if (! empty($applied)) {
            $applied = DateTime::createFromFormat('d/m/Y', $applied);
            $applied = $applied->format('Y-m-d');
            $candidates = $candidates->whereDate('applied_date', $applied);
        }

        return $candidates->orderBy('applied_date', 'DESC')->paginate($pagination)->setPath('');
    }

    public function getStartDate($daterange)
    {
        return Carbon::parse($daterange[0])->startOfDay()->toDateTimeString();
    }

    public function getEndDate($daterange)
    {
        return Carbon::parse($daterange[1])->endOfDay()->toDateTimeString();
    }

    public function getSchedule()
    {
        return Schedule::with('candidate')->where('recruitment_id', request('id'))->first();
    }

    public function updateSchedule($data)
    {
        $this->model::find(Schedule::find(request('schedule_id'))->recruitment_id)->update(['status' => request('status')]);
        Schedule::find(request('schedule_id'))->update($data);
    }

    public function getCandidatesForUpdateSchedule($pagination)
    {
        return $this->model::orderBy('applied_date', 'DESC')->where('created_at', 'like', Carbon::today()->toDateString().'%')->paginate($pagination);
    }
}
