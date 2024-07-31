<?php

namespace App\Repository;

use App\Models\AssetTicketStatus;

class AssetTicketStatusRepository
{
    protected $model;

    public function __construct(AssetTicketStatus $assetTicketStatus)
    {
        $this->model = $assetTicketStatus;
    }

    public function getTicketStatus()
    {
        return $this->model::orderBy('title', 'ASC')->get();
    }

    public function getAssetTicketStatusWhere($id)
    {
        return $this->model::where('id', $id)->first();
    }

    public function getTicketStatusForIndex($pagination)
    {
        return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
    }
}
