<?php

namespace App\Repository;

use App\Models\Asset;
use App\Models\AssetAttributeValue;
use App\Models\AssetType;
use App\Models\AssetUser;
use App\Models\AssetVendor;
use App\Models\Settings;
use Illuminate\Support\Carbon;

class AssetRepository
{
    protected $model;

    public function __construct(Asset $asset)
    {
        $this->model = $asset;
    }

    public function getAssets()
    {
        $assetsQuery = $this->model::with(['assetType', 'assetUser' => function ($query) {
            $query->with('user');
            $query->whereIn('status', ['allocated', 'ticket_raised']);
        }])->orderBy('created_at', 'DESC');

        return $assetsQuery;
    }

    public static function getList()
    {
        return Asset::orderBy('name', 'ASC');
    }

    public function getAssetTypes()
    {
        return AssetType::where('status', 'active')->orderBy('name', 'ASC')->get();
    }

    public function getAssetVendors()
    {
        return AssetVendor::where('status', 'active')->orderBy('name', 'ASC')->get();
    }

    public function getAllAccessTypes()
    {
        return AssetType::orderBy('name', 'ASC')->get();
    }

    public function getAllAssetVendors()
    {
        return AssetVendor::orderBy('name', 'ASC')->get();
    }

    /**
     * Retrieves assets based on the given filter parameters.
     *
     * @param  array  $filter  The filter parameters.
     * @return \Illuminate\Database\Eloquent\Builder The assets query builder.
     */
    public function getAssetsForSearch($filter)
    {
        $userId = $filter['user_id'];
        $status = $filter['status'];
        $assetId = $filter['asset_id'];
        $typeId = $filter['type_id'];
        $vendorId = $filter['vendor_id'];
        $allocationDateRange = $filter['daterange'];

        $attributeValueIds = [];
        if (isset($filter['attribute_value_ids']) && $filter['attribute_value_ids']) {
            $attributeValueIds = array_filter($filter['attribute_value_ids'], function ($value) {
                return $value !== '';
            });
        }

        $allocationFromDate = null;
        $allocationToDate = null;

        if ($allocationDateRange != '') {
            $daterange = explode(' - ', $allocationDateRange);
            $allocationFromDate = Carbon::parse($daterange[0])->startOfDay()->toDateTimeString();
            $allocationToDate = Carbon::parse($daterange[1])->endOfDay()->toDateTimeString();
        }

        $assetsQuery = $this->model::with(['assetType'])->whereNull('assets.deleted_at')->orderBy('created_at', 'DESC');

        $assetsQuery->when(! empty($userId), function ($q) use ($userId) {
            return $q->whereHas('assetUser', function ($query) use ($userId) {
                $query->where('user_id', $userId);
                $query->whereIn('status', ['allocated', 'ticket_raised']);
            });
        });

        $assetsQuery->when(! empty($allocationDateRange), function ($q) use ($allocationFromDate, $allocationToDate) {
            return $q->whereHas('assetUser', function ($query) use ($allocationFromDate, $allocationToDate) {
                $query->where('assigned_date', '>=', $allocationFromDate);
                $query->where('assigned_date', '<=', $allocationToDate);
            });
        });

        $assetsQuery->when(! empty($status), function ($q) use ($status) {
            return $q->where('assets.status', '=', $status);
        });

        $assetsQuery->when(! empty($assetId), function ($q) use ($assetId) {
            return $q->where('assets.id', $assetId);
        });

        $assetsQuery->when(! empty($typeId), function ($q) use ($typeId) {
            return $q->where('asset_type_id', $typeId);
        });

        $assetsQuery->when(! empty($vendorId), function ($q) use ($vendorId) {
            return $q->where('vendor_id', $vendorId);
        });

        $assetsQuery->when(! empty($attributeValueIds), function ($q) use ($attributeValueIds) {
            return $q->whereHas('assetAttributeValues', function ($query) use ($attributeValueIds) {
                $query->whereIn('attribute_value_id', $attributeValueIds);
            }, '=', count($attributeValueIds));
        });

        return $assetsQuery;
    }

    public function getAssetsForStore($pagination)
    {
        $assetsQuery = $this->model::orderBy('created_at', 'DESC');

        return $assetsQuery->paginate($pagination);
    }

    public function getAsset($id)
    {
        return $this->model::with('assetUser', 'assetAttributeValues.attribute_value.attribute', 'assetTicket', 'assetType', 'assetVendor', 'documents')->where('id', $id)->first();
    }

    public function getAssetForEdit($id)
    {
        return $this->model::with('allocatedUser', 'documents')->where('id', $id)->first();
    }

    public function getAssetUser($id = null)
    {
        $assetId = request('id') ?? $id;

        return AssetUser::where('asset_id', $assetId)->where('status', 'allocated')->orderBy('id', 'DESC')->first();
    }

    public function getAssetsForEmployeeAssetList($userId, $pagination)
    {
        return AssetUser::where('user_id', $userId)->whereIn('status', ['allocated', 'ticket_raised'])->with('asset')->has('asset')->paginate($pagination);
    }

    public function updateAssetUserWhere($id, $data)
    {
        AssetUser::where('id', $id)->update($data);
    }

    public function updateAssetWhere($id, $data)
    {
        $this->model::where('id', $id)->update($data);
    }

    public function getAllAssetsForId()
    {
        return $this->model::with('assetUser', 'assetTicket', 'assetType', 'assetVendor', 'documents')->where('id', request('asset_id'))->first();
    }

    public function getAssetUserWhere($id)
    {
        return AssetUser::where('id', $id)->first();
    }

    public function getAttributeValuesForAsset($id)
    {
        return AssetAttributeValue::where('asset_id', $id)->get();
    }

    public static function getCsvAssetData($request)
    {
        $assets = AssetRepository::fetchAssetsForCsv($request);
        foreach ($assets as $asset) {
            $depreciationValue = ($asset->value && $asset->purchased_date) ? AssetRepository::getAssetDepreciationValue($asset) : 0;
            $asset->depreciation_value = $depreciationValue;
        }

        return $assets;
    }

    public static function fetchAssetsForCsv($request)
    {
        $userId = $request->user_id;
        $status = $request->status;
        $assetId = $request->asset_id;
        $typeId = $request->type_id;
        $vendorId = $request->vendor_id;
        $allocationDateRange = $request->daterange;
        $attributeValueIds = [];
        if (! empty($request->attribute_value_ids)) {
            $attributeValueIds = array_filter($request->attribute_value_ids, function ($value) {
                return $value !== '';
            });
        }

        $allocationFromDate = null;
        $allocationToDate = null;

        if ($allocationDateRange != '') {
            $daterange = explode(' - ', $allocationDateRange);
            $allocationFromDate = Carbon::parse($daterange[0])->startOfDay()->toDateTimeString();
            $allocationToDate = Carbon::parse($daterange[1])->endOfDay()->toDateTimeString();
        }

        $assetsQuery = Asset::with(['assetType', 'assetAttributeValues', 'assetUser' => function ($query) {
            $query->with('user');
            $query->whereIn('status', ['allocated', 'ticket_raised']);
        }])->orderBy('created_at', 'DESC');

        $assetsQuery->when(! empty($userId), function ($q) use ($userId) {
            return $q->whereHas('assetUser', function ($query) use ($userId) {
                $query->where('user_id', $userId);
                $query->whereIn('status', ['allocated', 'ticket_raised']);
            });
        });

        $assetsQuery->when(! empty($allocationDateRange), function ($q) use ($allocationFromDate, $allocationToDate) {
            return $q->whereHas('assetUser', function ($query) use ($allocationFromDate, $allocationToDate) {
                $query->where('assigned_date', '>=', $allocationFromDate);
                $query->where('assigned_date', '<=', $allocationToDate);
            });
        });

        $assetsQuery->when(! empty($status), function ($q) use ($status) {
            return $q->where('status', '=', $status);
        });

        $assetsQuery->when(! empty($assetId), function ($q) use ($assetId) {
            return $q->where('id', $assetId);
        });

        $assetsQuery->when(! empty($typeId), function ($q) use ($typeId) {
            return $q->where('asset_type_id', $typeId);
        });

        $assetsQuery->when(! empty($vendorId), function ($q) use ($vendorId) {
            return $q->where('vendor_id', $vendorId);
        });

        $assetsQuery->when(! empty($attributeValueIds), function ($q) use ($attributeValueIds) {
            return $q->whereHas('assetAttributeValues', function ($query) use ($attributeValueIds) {
                $query->whereIn('attribute_value_id', $attributeValueIds);
            }, '=', count($attributeValueIds));
        });

        $assetData = $assetsQuery->get();

        $result = $assetData->map(function ($asset) {
            $attributeIds = $asset->assetAttributeValues->pluck('attribute_value.attribute_id')->toArray();
            $attributeValues = $asset->assetAttributeValues->pluck('attribute_value.value')->toArray();

            return [
                'id' => $asset->id,
                'attributes' => array_combine($attributeIds, $attributeValues),
            ];
        });

        $assetData = $assetData->map(function ($asset, $key) use ($result) {
            $attributes = $asset->getAttributes();
            $mergedAttributes = array_merge($attributes, $result[$key]);
            $asset->setRawAttributes($mergedAttributes, true);

            return $asset;
        });

        return $assetData;
    }

    public static function getAssetDepreciationValue($asset)
    {
        $depreciationValue = 0;
        $depreciationRate = $asset->assetType->depreciation_rate;
        if ($depreciationRate == '') {
            return $depreciationValue;
        }
        if (! ($asset->value && $asset->purchased_date)) {
            return $depreciationValue;
        }
        $purchaseDate = Carbon::createFromFormat('Y-m-d H:i:s', $asset->purchased_date);
        $currentDate = Carbon::now();

        if ($purchaseDate->gt($currentDate)) {
            return $depreciationValue;
        }
        $companyFinYear = Settings::getCompanyFinancialYear();
        if (! $companyFinYear) {
            return $depreciationValue;
        }
        $financialYearEndMonth = $companyFinYear['end']['month'].'-'.$companyFinYear['end']['day'];
        $assetValue = $asset->value;
        $startDate = $purchaseDate;
        for ($i = $purchaseDate->year; $i <= $currentDate->year; $i++) {
            $endDate = Carbon::createFromFormat('Y-m-d', "$i-".$financialYearEndMonth);

            if (! ($startDate->gt($endDate) && $i == $purchaseDate->year)) {
                $noOfDays = self::getNumberOfDays($startDate, $endDate);
                $assetValue = self::calculateDepreciation($assetValue, $noOfDays, $depreciationRate);
                $startDate = $endDate->copy()->addDay();
                if ($endDate->lt($currentDate) && $i == $currentDate->year) {
                    $noOfDays = self::getNumberOfDays($startDate, $currentDate);
                    $assetValue = self::calculateDepreciation($assetValue, $noOfDays, $depreciationRate);
                }
            }
        }

        return $assetValue > 0 ? $assetValue : 0;
    }

    public static function calculateDepreciation($value, $days, $depreciationRate)
    {
        $depreciatedValue = $value - ($value * ($depreciationRate / 100) * ($days / 365));

        return $depreciatedValue > 0 ? $depreciatedValue : 0;
    }

    public static function getNumberOfDays($startDate, $endDate)
    {
        $noOfDays = $startDate->diffInDays($endDate);
        $noOfDays = $noOfDays >= 365 ? 365 : ($noOfDays + 1);

        return $noOfDays;
    }
}
