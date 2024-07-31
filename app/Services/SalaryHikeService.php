<?php

namespace App\Services;

use App\Repository\SalaryHikeRepository;

class SalaryHikeService
{
    protected $salaryHikeRepository;

    public function __construct(SalaryHikeRepository $salaryHikeRepository)
    {
        $this->salaryHikeRepository = $salaryHikeRepository;
    }

    public function getEmployeeHikeHistory($currentYear, $pagination)
    {
        return $this->salaryHikeRepository->getEmployeeHikeHistory($currentYear, $pagination);
    }

    public function getEmployeeHikeHistoryForUserId($hikeHistory)
    {
        return $this->salaryHikeRepository->getEmployeeHikeHistoryForUserId($hikeHistory);
    }

    public function getHikeHistoryForEmployeeId($employeeId)
    {
        return $this->salaryHikeRepository->getHikeHistoryForEmployeeId($employeeId);
    }

    public function getEmployeeHikeHistoryForSearch($pagination)
    {
        return $this->salaryHikeRepository->getEmployeeHikeHistoryForSearch($pagination);
    }

    public function getEmployeesWithoutHike()
    {
        return $this->salaryHikeRepository->getEmployeesWithoutHike();
    }

    public function store()
    {
        $this->salaryHikeRepository->store();
    }
}
