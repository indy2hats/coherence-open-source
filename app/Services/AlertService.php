<?php

namespace App\Services;

use App\Repository\AlertRepository;

class AlertService
{
    protected $alertRepository;

    public function __construct(AlertRepository $alertRepository)
    {
        $this->alertRepository = $alertRepository;
    }

    public function storeAndGetUserWishList()
    {
        return $this->alertRepository->storeAndGetUserWishList();
    }

    public function deleteAndGetUserWishList($id)
    {
        return $this->alertRepository->deleteAndGetUserWishList($id);
    }
}
