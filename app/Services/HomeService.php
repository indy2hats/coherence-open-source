<?php

namespace App\Services;

use App\Repository\HomeRepository;
use App\Traits\GeneralTrait;

class HomeService
{
    use GeneralTrait;

    protected $homeRepository;

    public function __construct(HomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    public function sendAlert()
    {
        $user = $this->getUser();
        if ($user->hasRole('employee|project-manager|client')) {
            $user->notify(new \App\Notifications\IdlePush('title', 'body'));
        }
    }

    public function getUser()
    {
        return $this->homeRepository->getUser();
    }

    public function updatePassword()
    {
        $this->updateUser($this->getCurrentUserId(), ['password' => request('password'), 'must_change_password' => 0]);
    }

    public function search($request)
    {
        return $this->homeRepository->search($request);
    }

    public function getDetails()
    {
        return $this->homeRepository->getDetails();
    }

    public function getCountDetails()
    {
        return $this->homeRepository->getCountDetails();
    }

    public function getTotalHours()
    {
        return $this->homeRepository->getTotalHours();
    }

    public function getThisWeek()
    {
        return $this->homeRepository->getThisWeek();
    }

    public function getLeaves()
    {
        return $this->homeRepository->getLeaves();
    }

    public function getRejectionCount()
    {
        return $this->homeRepository->getRejectionCount();
    }

    public function getRejections()
    {
        return $this->homeRepository->getRejections();
    }

    public function getRejectionIndex()
    {
        return $this->homeRepository->getRejectionIndex();
    }

    public function getClientProjectsCount()
    {
        return $this->homeRepository->getClientProjectsCount();
    }

    public function getClientCompanies()
    {
        return $this->homeRepository->getClientCompanies();
    }
}
