<?php

namespace App\Repository;

use App\Models\Asset;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeRepository
{
    protected $model;

    public function __construct(Attribute $attribute)
    {
        $this->model = $attribute;
    }

    public function getAttributes($pagination)
    {
        return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
    }

    public static function getAllAttributes()
    {
        return Attribute::orderBy('created_at', 'DESC')->get();
    }

    public function getAttributeWhere($id)
    {
        return $this->model::where('id', $id)->first();
    }

    public function getAttributeValues($attributeId)
    {
        return AttributeValue::where('attribute_id', $attributeId)->get();
    }

    // public function typeAssetsCount($id)
    // {
    //     return Asset::where('asset_type_id', $id)->count();
    // }
}
