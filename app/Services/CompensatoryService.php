<?php

namespace App\Services;

use App\Repository\CompensatoryRepository;
use Illuminate\Support\Carbon;

class CompensatoryService
{
    protected $compensatoryRepository;

    public function __construct(CompensatoryRepository $compensatoryRepository)
    {
        $this->compensatoryRepository = $compensatoryRepository;
    }

    public function getCompensatoryList($year)
    {
        return $this->compensatoryRepository->getCompensatoryList($year);
    }

    public function getIp()
    {
        return Carbon::createFromFormat('d/m/Y', request('date'))->format('Y-m-d');
    }

    public function createCompensatory()
    {
        return $this->compensatoryRepository->createCompensatory();
    }

    public function updateCompensatory($id)
    {
        return $this->compensatoryRepository->updateCompensatory($id);
    }

    public function getUsers()
    {
        return $this->compensatoryRepository->getUsers();
    }

    public function getPendingList()
    {
        return $this->compensatoryRepository->getPendingList();
    }

    public function getPrevious($year)
    {
        return $this->compensatoryRepository->getPrevious($year);
    }

    public function getPreviousForAcceptRejectApplication()
    {
        return $this->compensatoryRepository->getPreviousForAcceptRejectApplication();
    }

    public function acceptApplication($id)
    {
        return $this->compensatoryRepository->acceptApplication($id);
    }

    public function rejectApplication()
    {
        return $this->compensatoryRepository->rejectApplication();
    }

    public function getPreviousForApplicationSearch()
    {
        return $this->compensatoryRepository->getPreviousForApplicationSearch();
    }

    public function isCompensatoryAlreadyAppliedIntheDate($userId, $appliedDate)
    {
        return $this->compensatoryRepository->isExist($userId, $appliedDate);
    }
}
