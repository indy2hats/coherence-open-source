<?php

namespace App\Repository;

use App\Models\Asset;
use App\Models\AssetAttributeValue;
use App\Models\AssetType;
use App\Models\AssetTypeAttribute;
use App\Models\AttributeValue;
use Exception;
use Illuminate\Support\Facades\Log;

class AssetTypeRepository
{
    protected $model;
    protected $assetTypeAttribute;

    public function __construct(AssetType $assetType, AssetTypeAttribute $assetTypeAttribute)
    {
        $this->model = $assetType;
        $this->assetTypeAttribute = $assetTypeAttribute;
    }

    public function getAssetTypes($pagination)
    {
        return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
    }

    public function getAssetTypeWhere($id)
    {
        return $this->model::where('id', $id)->first();
    }

    public function typeAssetsCount($id)
    {
        return Asset::where('asset_type_id', $id)->count();
    }

    public function tagAttributesToAssetType($id, $newAttributeIds)
    {
        try {
            $existingAttributeIds = [];
            $newAttributeIds = $newAttributeIds ?? [];
            $existingAttributeIds = $this->assetTypeAttribute->where('asset_type_id', $id)->pluck('attribute_id')->toArray();

            $attributesToAdd = array_diff($newAttributeIds, $existingAttributeIds);
            $attributesToDelete = array_diff($existingAttributeIds, $newAttributeIds);

            if ($attributesToDelete) {
                $this->deleteAssetTypeAttributes($id, $attributesToDelete);
            }

            if ($attributesToAdd) {
                foreach ($attributesToAdd as $attribute) {
                    $this->assetTypeAttribute->create([
                        'asset_type_id' => $id,
                        'attribute_id' => $attribute,
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::info('Failed to associate attributes to an asset type '.$e);
        }
    }

    public function deleteAssetTypeAttributes($assetTypeId, $attributeIds)
    {
        $assetsWithAssetTypeId = Asset::where('asset_type_id', $assetTypeId)->pluck('id')->toArray();
        $attributeValuesWithAttributeIds = AttributeValue::whereIn('attribute_id', $attributeIds)->pluck('id')->toArray();

        AssetAttributeValue::whereIn('asset_id', $assetsWithAssetTypeId)->whereIn('attribute_value_id', $attributeValuesWithAttributeIds)->delete();
        $this->assetTypeAttribute->where('asset_type_id', $assetTypeId)->whereIn('attribute_id', $attributeIds)->delete();
    }

    public function getAttributesForAssetType($id)
    {
        return $this->assetTypeAttribute
            ->where('asset_type_id', $id)
            ->with(['attribute'])
            ->get();
    }
}
