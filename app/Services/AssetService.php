<?php

namespace App\Services;

use App\Exports\AssetExcelExport;
use App\Models\Asset;
use App\Models\AssetUser;
use App\Repository\AssetRepository;
use App\Traits\GeneralTrait;
use DateTime;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class AssetService
{
    use GeneralTrait;

    protected $assetRepository;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    public function getAssets()
    {
        return $this->assetRepository->getAssets()->get();
    }

    public function getList()
    {
        return $this->assetRepository->getList()->get();
    }

    public function getAssetTypes()
    {
        return $this->assetRepository->getAssetTypes();
    }

    public function getAssetVendors()
    {
        return $this->assetRepository->getAssetVendors();
    }

    public function getAllAccessTypes()
    {
        return $this->assetRepository->getAllAccessTypes();
    }

    public function getAllAssetVendors()
    {
        return $this->assetRepository->getAllAssetVendors();
    }

    public function getAssetsForStore($pagination)
    {
        return $this->assetRepository->getAssetsForStore($pagination);
    }

    public function store($request)
    {
        $files = [];
        $data = [
            'asset_type_id' => request('asset_type_id'),
            'configuration' => request('configuration'),
            'name' => request('asset_name'),
            'purchased_date' => request('purchased_date') ? $this->getPurchasedDate() : null,
            'serial_number' => request('serial_number'),
            'vendor_id' => request('vendor'),
            'warranty' => request('warranty') ? $this->getWarranty() : null,
            'status' => request('status'),
            'value' => request('asset_value')
        ];

        $asset = $this->createAsset($data);
        if ($request->attributeValues) {
            foreach ($request->attributeValues as $attributeValueId) {
                if ($attributeValueId != '') {
                    $assetAttributeData = [
                        'asset_id' => $asset->id,
                        'attribute_value_id' => $attributeValueId,
                    ];

                    $this->createAssetAttributeValues($assetAttributeData);
                }
            }
        }

        request('status') == 'allocated' ? $this->allocateAssetUser($asset->id) : '';
        if ($request->hasFile('files')) {
            $files = $this->multipleUpload(request('files'));
        }

        foreach ($files as $file) {
            $this->createAssetDocument([
                'asset_id' => $asset->id,
                'path' => $file
            ]);
        }
    }

    public function getPurchasedDate()
    {
        return Carbon::createFromFormat('d/m/Y', request('purchased_date'))->format('Y-m-d');
    }

    public function getWarranty()
    {
        return Carbon::createFromFormat('d/m/Y', request('warranty'))->format('Y-m-d');
    }

    /**
     * The function "multipleUpload" takes an array of documents, renames them with a timestamp, and
     * stores them in a specified directory, returning an array of the file paths.
     *
     * @param array docs An array of files to be uploaded.
     * @return an array of file paths.
     */
    public function multipleUpload(array $docs)
    {
        $files = [];
        foreach ($docs as $doc) {
            $docName = time().$doc->getClientOriginalName();
            $files[] = $doc->storeAs('assets/documents', $docName);
        }

        return $files;
    }

    public function getAsset($id)
    {
        return $this->assetRepository->getAsset($id);
    }

    public function getAssetForEdit($id)
    {
        return $this->assetRepository->getAssetForEdit($id);
    }

    public function update($request, $id)
    {
        $files = [];
        $data = [
            'asset_type_id' => request('asset_type_id'),
            'configuration' => request('configuration'),
            'name' => request('asset_name'),
            'purchased_date' => request('purchased_date') ? $this->getPurchasedDate() : null,
            'serial_number' => request('serial_number'),
            'status' => request('status'),
            'vendor_id' => request('vendor'),
            'warranty' => request('warranty') ? $this->getWarranty() : null,
            'value' => request('asset_value')
        ];

        $this->updateAsset($id, $data);

        $this->manageAssetUser($id);

        $this->deleteAssetAttributeValues($id);
        if ($request->attributeValues) {
            foreach ($request->attributeValues as $attributeValueId) {
                if ($attributeValueId != '') {
                    $assetAttributeData = [
                        'asset_id' => $id,
                        'attribute_value_id' => $attributeValueId,
                    ];

                    $this->createAssetAttributeValues($assetAttributeData);
                }
            }
        }

        if ($request->hasFile('files')) {
            $files = $this->multipleUpload(request('files'));
        }

        foreach ($files as $file) {
            $this->createAssetDocument([
                'asset_id' => $id,
                'path' => $file
            ]);
        }
    }

    public function manageAssetUser($id)
    {
        $assetUser = $this->getAssetUser($id); // check if the asset is allocated already
        if ($assetUser) {
            if (request('status') != 'allocated') {
                $this->deAllocateAssetUser($assetUser->id);
            }

            if (request('status') == 'allocated') {
                if ($assetUser->user_id == request('user_id')) { // if same user, then update
                    $this->modifyAssetUser($assetUser->id);
                } else {        // if different user, deallocate the existing user and allocate to new user
                    $this->deAllocateAssetUser($assetUser->id);
                    $this->allocateAssetUser($id);
                }
            }

            return;
        }

        if (request('status') == 'allocated') {
            $this->allocateAssetUser($id);

            return;
        }
    }

    public function deAllocateAssetUser($id)
    {
        AssetUser::where('id', $id)->update([
            'status' => 'inactive'
        ]);
    }

    public function allocateAssetUser($id)
    {
        $this->createAssetUser([
            'user_id' => request('user_id'),
            'asset_id' => $id,
            'assigned_date' => Carbon::createFromFormat('d/m/Y', request('assigned_date'))->format('Y-m-d'),
            'status' => 'allocated'
        ]);
    }

    public function modifyAssetUser($id)
    {
        $this->updateAssetUser($id, [
            'user_id' => request('user_id'),
            'assigned_date' => Carbon::createFromFormat('d/m/Y', request('assigned_date'))->format('Y-m-d'),
        ]);
    }

    // public function assignAsset($assetId)
    // {
    //     $asset = $this->getAssetUser();
    //     if ($asset) {
    //         AssetUser::where('id', $asset->id)->update([
    //             'status' => 'inactive'
    //         ]);
    //     }
    //     $this->createAssetUser([
    //         'user_id' => request('user_id'),
    //         'asset_id' => request('id'),
    //         'assigned_date' => Carbon::createFromFormat('d/m/Y', request('assigned_date'))->format('Y-m-d'),
    //         'status' => 'allocated'
    //     ]);

    //     $this->updateAsset(request('id'), [
    //         'status' => 'allocated'
    //     ]);
    // }

    public function getAssetUser($id = null)
    {
        return $this->assetRepository->getAssetUser($id);
    }

    public function getAssetsForEmployeeAssetList($userId, $pagination)
    {
        return $this->assetRepository->getAssetsForEmployeeAssetList($userId, $pagination);
    }

    public function updateAssetUserWhere($id, $data)
    {
        return $this->assetRepository->updateAssetUserWhere($id, $data);
    }

    public function updateAssetWhere($id, $data)
    {
        return $this->assetRepository->updateAssetWhere($id, $data);
    }

    public function getAllAssetsForId()
    {
        return $this->assetRepository->getAllAssetsForId();
    }

    public function getAssetUserWhere($id)
    {
        return $this->assetRepository->getAssetUserWhere($id);
    }

    public function getAttributeValuesForAsset($id)
    {
        return $this->assetRepository->getAttributeValuesForAsset($id);
    }

    public function exportExcelAssets()
    {
        $response = Excel::download(new AssetExcelExport(request()), 'Assets.xlsx');
        ob_end_clean();

        return $response;
    }

    public function getAssetsCount()
    {
        return $this->assetRepository->getAssets()->count();
    }

    public function getAssetsCountWithFilter($request)
    {
        $filter = $request['filter'];

        return $this->assetRepository->getAssetsForSearch($filter)->count();
    }

    public function getAllAssets($request)
    {
        $offset = $request->get('start') ?? 0;
        $limit = $request->get('length') ?? 25;
        $columns = [
            0 => 'name',
            1 => 'assetType',
            2 => 'first_name',
            3 => 'serial_number',
            4 => 'purchased_date',
            5 => 'value',
            6 => 'status',
            7 => 'action',
        ];
        $sortColumn = $request->input('order.0.column') ? $columns[$request->input('order.0.column')] : 'created_by';
        $sort = $request->input('order.0.dir') == 'desc' ? 'sortByDesc' : 'sortBy';
        $assets = [];
        $assetValue = 0;
        $assetDepreciatedValue = 0;
        try {
            $assetQuery = $this->filterAssets($request['filter']);
            $assetValue = $assetQuery->sum('value');
            $assets = $assetQuery->get();
            $assetDepreciatedValue = $this->getAssetTotalDepreciatedValue($assets) ?? 0;
            $assets = $assets
                        ->$sort($sortColumn)
                        ->skip($offset)
                        ->take($limit);
        } catch (Exception $e) {
            Log::error('Error while getting filtered assets : '.$e->getMessage());
        }

        return [
            'assetValue' => $assetValue,
            'assets' => $assets,
            'assetDepreciatedValue' => $assetDepreciatedValue,
        ];
    }

    public function filterAssets($filter)
    {
        $assets = $this->assetRepository->getAssetsForSearch($filter);

        return $assets;
    }

    public function getAssetTotalDepreciatedValue($assets)
    {
        $depreciatedValue = 0;
        if (! $assets) {
            return $depreciatedValue;
        }
        foreach ($assets as $asset) {
            $depreciatedValue += $this->assetRepository->getAssetDepreciationValue($asset) ?? 0;
        }

        return $depreciatedValue;
    }

    public function formatAssets($assets)
    {
        $dataArray = [];
        if (! $assets) {
            return $dataArray;
        }
        foreach ($assets as $asset) {
            $assetName = "<a class='dropdown-item' href='assets/".$asset->id."'>$asset->name</a>";
            $employeeName = $asset->status == 'allocated' ? (count($asset->assetUser) > 0 ? $asset->assetUser[0]->user->full_name : '') : '';
            $assetStatus = $this->getAssetStatusForDisplay($asset->status);
            $assetAction = $this->getActionsForAsset($asset);
            $depreciationValue = ($asset->value && $asset->purchased_date) ? $this->assetRepository->getAssetDepreciationValue($asset) : 0;
            $dataArray[] = [
                'assetName' => $assetName,
                'assetType' => $asset->assetType->name ?? '',
                'employeeName' => $employeeName,
                'assetSerialNo' => $asset->serial_number,
                'assetDOP' => $asset->purchased_date ? date_format(new DateTime($asset->purchased_date), 'F d, Y') : '',
                'assetValue' => $asset->value ? number_format($asset->value, 2) : '',
                'assetDepreciationValue' => number_format($depreciationValue, 2),
                'assetStatus' => $assetStatus,
                'action' => $assetAction,
            ];
        }

        return $dataArray;
    }

    public function getAssetStatusForDisplay($status)
    {
        $assetStatus = '';
        switch ($status) {
            case 'allocated':
                $assetStatus = "<label class='label label-success'>".ucwords(str_replace('_', ' ', $status)).'</label>';
                break;
            case 'inactive':
                $assetStatus = "<label class='label label-info'>".ucwords(str_replace('_', ' ', $status)).'</label>';
                break;
            case 'non_allocated':
                $assetStatus = "<label class='label label-danger'>".ucwords(str_replace('_', ' ', $status)).'</label>';
                break;
            case 'ticket_raised':
                $assetStatus = "<label class='label label-warning'>".ucwords(str_replace('_', ' ', $status)).'</label>';
                break;
            default:
                $assetStatus = '';
                break;
        }

        return $assetStatus;
    }

    public function getActionsForAsset($asset)
    {
        $assetAction = '';
        if (Gate::allows('manage-assets')) {
            $assetAction = "<a class='dropdown-item' href='assets/".$asset->id."'  data-tooltip='tooltip'
            data-placement='top' title='View' style='margin-right: 10px;'><i class='ri-eye-line'></i></a>";

            $assetAction .= "<a class='dropdown-item edit-asset' href='#' data-id='".$asset->id."'
                            data-tooltip='tooltip' data-placement='top' title='Edit' style='margin-right: 10px;'> <i class='ri-pencil-line m-r-5'></i></a>";

            $assetAction .= "<a class='dropdown-item delete-asset' href='#' data-toggle='modal'
            data-target='#delete_asset' data-id='".$asset->id."' data-tooltip='tooltip'
            data-placement='top' title='Delete' style='margin-right: 10px;'><i class='ri-delete-bin-line m-r-5'></i></a>";

            if (Gate::allows('manage-assets')) {
                $allocationLink = $asset->status == 'allocated' ? '' : 'hide-link';
                $userCount = count($asset->assetUser) > 0 ? $asset->assetUser[0]->id : '';
                $assetAction .= "<a class='dropdown-item ticket-raise-asset".$allocationLink."' href='#' data-toggle='modal'
                data-target='#ticket_raise_asset' data-id='".$userCount."' data-tooltip='tooltip'
                data-placement='top' title='Report Asset Issue'><i class='ri-ticket-line'></i></a>";
            }
        }

        return $assetAction;
    }
}
