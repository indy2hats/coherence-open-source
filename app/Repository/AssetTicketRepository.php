<?php

namespace App\Repository;

use App\Models\Asset;
use App\Models\AssetTicket;

class AssetTicketRepository
{
    protected $model;

    public function __construct(AssetTicket $assetTicket)
    {
        $this->model = $assetTicket;
    }

    public function getAssetTickets($userId, $pagination)
    {
        return $this->model::where('user_id', $userId)->with('assetUser', 'asset', 'user', 'ticketStatus')->has('asset')->orderBy('id', 'DESC')->paginate($pagination);
    }

    public function getAssetTicketsForSearchEmployee($userId, $pagination)
    {
        $status = request()->status;

        $assetsQuery = $this->model::where('user_id', $userId)->with('assetUser', 'asset', 'user')->has('asset');

        $assetsQuery->when(! empty($status), function ($q) use ($status) {
            return $q->where('status', '=', $status);
        });

        return $assetsQuery->orderBy('id', 'DESC')->paginate($pagination);
    }

    public function getAssetTicketWhere($id)
    {
        return $this->model::where('id', $id)->first();
    }

    public function getAssetTicketsForIssueUpdate($userId, $pagination)
    {
        $assetsQuery = Asset::orderBy('created_at', 'DESC');
        $assets = $assetsQuery->paginate($pagination);

        $assets = $this->model::where('user_id', $userId)->whereHas('assetUser', function ($query) {
            $query->where('status', 'ticket_raised');
        })->with('asset', 'user')->paginate($pagination);

        return $assets;
    }

    public function getAssetTicketsForSearch($pagination)
    {
        $userId = request()->user_id;
        $status = request()->status;
        $assetId = request()->asset_id;
        $statusId = request()->resolving_status;

        $assetsQuery = $this->model::with('asset', 'user')->has('asset')->orderBy('id', 'DESC');
        $assetsQuery->when(! empty($userId), function ($q) use ($userId) {
            return $q->whereHas('assetUser', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
        });

        $assetsQuery->when(! empty($status), function ($q) use ($status) {
            return $q->where('status', '=', $status);
        });

        $assetsQuery->when(! empty($assetId), function ($q) use ($assetId) {
            return $q->whereHas('asset', function ($query) use ($assetId) {
                $query->where('id', $assetId);
            });
        });

        $assetsQuery->when(! empty($statusId), function ($q) use ($statusId) {
            return $q->where('status_id', $statusId);
        });

        return $assetsQuery->paginate($pagination);
    }

    public function updateAssetTicketWhere($id, $data)
    {
        $this->model::where('id', $id)->update($data);
    }

    public function statusTicketsCount($id)
    {
        return $this->model::where('status_id', $id)->has('asset')->count();
    }

    public function assetsForTicketRaisedAssetList($pagination)
    {
        return $this->model::with('asset', 'user', 'ticketStatus')->has('asset')->orderBy('id', 'DESC')->paginate($pagination);
    }
}
