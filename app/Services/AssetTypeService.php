<?php

namespace App\Services;

use App\Repository\AssetTypeRepository;

class AssetTypeService
{
    protected $assetTypeRepository;

    public function __construct(AssetTypeRepository $assetTypeRepository)
    {
        $this->assetTypeRepository = $assetTypeRepository;
    }

    public function getAssetTypes($pagination)
    {
        return $this->assetTypeRepository->getAssetTypes($pagination);
    }

    public function getAssetTypeWhere($id)
    {
        return $this->assetTypeRepository->getAssetTypeWhere($id);
    }

    public function typeAssetsCount($id)
    {
        return $this->assetTypeRepository->typeAssetsCount($id);
    }

    public function tagAttributesToAssetType($id, $attributes)
    {
        return $this->assetTypeRepository->tagAttributesToAssetType($id, $attributes);
    }

    public function getAttributesForAssetType($id)
    {
        return $this->assetTypeRepository->getAttributesForAssetType($id);
    }
}
