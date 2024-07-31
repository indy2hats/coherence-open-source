<?php

namespace App\Services;

use App\Repository\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllActiveEmployees()
    {
        return $this->userRepository->getAllActiveEmployees();
    }

    public function getUsers()
    {
        return $this->userRepository->getUsers();
    }

    public function getUser()
    {
        return $this->userRepository->getUser();
    }

    public function store($request)
    {
        $this->userRepository->store($request);
    }

    public function update($request, $id)
    {
        return $this->userRepository->update($request, $id);
    }

    public function getUserWithDesignationAndDept($id)
    {
        return $this->userRepository->getUserWithDesignationAndDept($id);
    }

    public function destroy($id)
    {
        $this->userRepository->destroy($id);
    }

    public function getSingleUser($Id)
    {
        return $this->userRepository->getSingleUser($Id);
    }

    public function getUsersForUsersGridAjax($request)
    {
        return $this->userRepository->getUsersForUsersGridAjax($request);
    }

    public function wishNotified()
    {
        $this->userRepository->wishNotified();
    }

    public function eodReportNotified()
    {
        $this->userRepository->eodReportNotified();
    }
}
