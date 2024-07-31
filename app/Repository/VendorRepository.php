<?php

namespace App\Repository;

use App\Models\Asset;
use App\Models\AssetVendor;

class VendorRepository
{
    protected $model;

    public function __construct(AssetVendor $assetVendor)
    {
        $this->model = $assetVendor;
    }

    public function getAssetVendors($pagination)
    {
        return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
    }

    public function assetVendorWhere($id)
    {
        return $this->model::where('id', $id)->first();
    }

    public function vendorAssetsCount($id)
    {
        return Asset::where('vendor_id', $id)->count();
    }
}
