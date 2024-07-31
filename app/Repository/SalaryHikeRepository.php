<?php

namespace App\Repository;

use App\Models\EmployeeHikeHistory;
use App\Models\User;
use App\Traits\GeneralTrait;

class SalaryHikeRepository
{
    use GeneralTrait;

    protected $model;

    public function __construct(EmployeeHikeHistory $employeeHikeHistory)
    {
        $this->model = $employeeHikeHistory;
    }

    public function getEmployeeHikeHistory($currentYear, $pagination)
    {
        return $this->model::with('user')
                ->whereYear('date', $currentYear)
                ->paginate($pagination);
    }

    public function getEmployeeHikeHistoryForUserId($hikeHistory)
    {
        return $this->model::where('user_id', $hikeHistory->user_id)
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getHikeHistoryForEmployeeId($employeeId)
    {
        return $this->model::where('user_id', $employeeId)->get();
    }

    public function getEmployeeHikeHistoryForSearch($pagination)
    {
        $userId = request()->user_id;
        $year = request()->year;

        $employeeHikeQuery = $this->model::orderBy('created_at', 'DESC');
        if (! empty($userId)) {
            $employeeHikeQuery->where('user_id', $userId);
        }

        if (! empty($year)) {
            $employeeHikeQuery->whereYear('date', $year);
        }

        return $employeeHikeQuery->paginate($pagination);
    }

    /**
     * Retrieves a collection of users who have not had a salary hike within the current month.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEmployeesWithoutHike()
    {
        return  User::getEmployees()
                    ->whereDoesntHave('employeeHikeHistory', function ($query) {
                        $query->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]);
                    })
                    ->get();
    }

    /**
     * Store a new salary hike record and update the corresponding user's monthly salary.
     *
     * @return void
     */
    public function store()
    {
        $data = [
            'user_id' => request('employee'),
            'hike' => request('hike'),
            'previous_salary' => request('previous_salary'),
            'updated_salary' => request('updated_salary'),
            'date' => request('date'),
            'notes' => request('notes')
        ];

        $user = $this->getUserByid(request('employee'));
        $user->monthly_salary = request('updated_salary');
        $user->save();

        $this->model::create($data);
    }
}
