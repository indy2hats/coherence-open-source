<?php

namespace App\Services;

use App\Repository\AttributeRepository;

class AttributeService
{
    protected $attributeRepository;

    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    public function getAttributes($pagination)
    {
        return $this->attributeRepository->getAttributes($pagination);
    }

    public function getAllAttributes()
    {
        return $this->attributeRepository->getAllAttributes();
    }

    public function getAttributeForEdit($id)
    {
        return $this->attributeRepository->getAttributeWhere($id);
    }

    public function getAttributeValues($attributeId)
    {
        return $this->attributeRepository->getAttributeValues($attributeId);
    }

    public function tagAttributesToAssetType($assetTypeId, $attributes)
    {
        return $this->attributeRepository->tagAttributesToAssetType($assetTypeId, $attributes);
    }

    // public function typeAssetsCount($id)
    // {
    //     return $this->assetTypeRepository->typeAssetsCount($id);
    // }
}
