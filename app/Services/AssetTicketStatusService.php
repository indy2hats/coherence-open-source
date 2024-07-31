<?php

namespace App\Services;

use App\Repository\AssetTicketStatusRepository;
use App\Traits\GeneralTrait;

class AssetTicketStatusService
{
    use GeneralTrait;

    protected $assetTicketStatusRepository;

    public function __construct(AssetTicketStatusRepository $assetTicketStatusRepository)
    {
        $this->assetTicketStatusRepository = $assetTicketStatusRepository;
    }

    public function getTicketStatus()
    {
        return $this->assetTicketStatusRepository->getTicketStatus();
    }

    public function getAssetTicketStatusWhere($id)
    {
        return $this->assetTicketStatusRepository->getAssetTicketStatusWhere($id);
    }

    public function getTicketStatusForIndex($pagination)
    {
        return $this->assetTicketStatusRepository->getTicketStatusForIndex($pagination);
    }
}
