<?php

namespace App\Services;

use App\Repository\VendorRepository;

class AssetVendorService
{
    protected $vendorRepository;

    public function __construct(VendorRepository $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    public function getAssetVendors($pagination)
    {
        return $this->vendorRepository->getAssetVendors($pagination);
    }

    public function assetVendorWhere($id)
    {
        return $this->vendorRepository->assetVendorWhere($id);
    }

    public function vendorAssetsCount($id)
    {
        return $this->vendorRepository->vendorAssetsCount($id);
    }
}
