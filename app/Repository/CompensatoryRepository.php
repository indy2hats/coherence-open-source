<?php

namespace App\Repository;

use App\Models\Compensatory;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;

class CompensatoryRepository
{
    use GeneralTrait;

    protected $model;

    public function __construct(Compensatory $compensatory)
    {
        $this->model = $compensatory;
    }

    public function getCompensatoryList($year)
    {
        $list = $this->model::whereYear('date', '=', $year);

        if (! $this->getCurrentUser()->can('manage-leave')) {
            $list = $list->whereUserId($this->getCurrentUserId());
        }

        return $list->orderBy('date', 'DESC')->get();
    }

    public function createCompensatory()
    {
        $data = [
            'date' => $this->getDate(),
            'session' => request('session'),
            'reason' => strip_tags(request('reason')),
            'user_id' => $this->getCurrentUserId(),
            'status' => 'Pending'
        ];

        $this->model::create($data);
    }

    public function updateCompensatory($id)
    {
        $data = [
            'date' => $this->getDate(),
            'session' => request('session'),
            'reason' => strip_tags(request('reason')),
            'status' => request('status'),
            'reason_for_rejection' => request('remarks'),
        ];

        $this->model::find($id)->update($data);
    }

    public function getDate()
    {
        return Carbon::createFromFormat('d/m/Y', request('date'))->format('Y-m-d');
    }

    public function getUsers()
    {
        return User::notClients()->active()->orderBy('first_name', 'ASC')->get();
    }

    public function getPendingList()
    {
        return $this->model::with('users')->where('status', 'Pending')->get();
    }

    public function getPrevious($year)
    {
        return $this->model::whereRaw('YEAR(date) ='.$year)->where('status', '!=', 'Pending')->orderBy('date', 'DESC')->get();
    }

    public function getPreviousForAcceptRejectApplication()
    {
        return $this->model::where('date', 'like', '%'.request('date').'%')->where('status', '!=', 'Pending')->orderBy('date', 'DESC')->get();
    }

    public function acceptApplication($id)
    {
        return $this->model::find($id)->update(['status' => 'Approved', 'approved_by' => $this->getCurrentUserId()]);
    }

    public function rejectApplication()
    {
        return $this->model::find(request('application_id'))->update(['status' => 'Rejected', 'reason_for_rejection' => request('reason'), 'approved_by' => $this->getCurrentUserId()]);
    }

    public function getPreviousForApplicationSearch()
    {
        $previous = $this->model::with('users');

        $user = request('user_id');

        $date = request('date');

        $previous->when(! empty($user), function ($q) use ($user) {
            return $q->where('user_id', $user);
        });

        $previous->when(! empty($date), function ($q) {
            return $q->where('date', 'like', '%'.request('date').'%');
        });

        return $previous->where('status', '!=', 'Pending')->orderBy('date', 'DESC')->get();
    }

    public function isExist($userId, $appliedDate)
    {
        return $this->model::where('user_id', $userId)
            ->where('date', $appliedDate)
            ->whereNull('reason_for_rejection')
            ->exists();
    }
}
