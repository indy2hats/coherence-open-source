<?php

namespace App\Services;

use App\Repository\AssetTicketRepository;
use App\Traits\GeneralTrait;

class AssetTicketService
{
    use GeneralTrait;

    protected $assetTicketRepository;

    public function __construct(AssetTicketRepository $assetTicketRepository)
    {
        $this->assetTicketRepository = $assetTicketRepository;
    }

    /**
     * Retrieves the asset tickets for a given user.
     *
     * @param  int  $userId  The ID of the user.
     * @param  int  $pagination  The number of tickets to retrieve per page.
     * @return \Illuminate\Pagination\LengthAwarePaginator The paginated list of asset tickets.
     */
    public function getAssetTickets($userId, $pagination)
    {
        return $this->assetTicketRepository->getAssetTickets($userId, $pagination);
    }

    public function getAssetTicketsForSearchEmployee($userId, $pagination)
    {
        return $this->assetTicketRepository->getAssetTicketsForSearchEmployee($userId, $pagination);
    }

    public function getAssetTicketWhere($id)
    {
        return $this->assetTicketRepository->getAssetTicketWhere($id);
    }

    public function getAssetTicketsForIssueUpdate($userId, $pagination)
    {
        return $this->assetTicketRepository->getAssetTicketsForIssueUpdate($userId, $pagination);
    }

    public function getAssetTicketsForSearch($pagination)
    {
        return $this->assetTicketRepository->getAssetTicketsForSearch($pagination);
    }

    public function updateAssetTicketWhere($id, $data)
    {
        return $this->assetTicketRepository->updateAssetTicketWhere($id, $data);
    }

    public function statusTicketsCount($id)
    {
        return $this->assetTicketRepository->statusTicketsCount($id);
    }

    public function assetsForTicketRaisedAssetList($pagination)
    {
        return $this->assetTicketRepository->assetsForTicketRaisedAssetList($pagination);
    }
}
