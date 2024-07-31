<?php

namespace App\Http\Controllers\ItAssets;

use App\Http\Controllers\Controller;
use App\Services\AssetService;
use App\Services\AssetTypeService;
use App\Services\AttributeService;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssetsController extends Controller
{
    use GeneralTrait;

    public $pagination;
    public $assetService;
    public $attributeService;
    public $assetTypeService;

    public function __construct(AssetService $assetService, AttributeService $attributeService, AssetTypeService $assetTypeService)
    {
        $this->pagination = config('general.pagination');
        $this->assetService = $assetService;
        $this->attributeService = $attributeService;
        $this->assetTypeService = $assetTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = $this->getUserNotClients();
        $list = $this->assetService->getList();
        $assetTypes = $this->assetService->getAssetTypes();
        $attributes = $this->attributeService->getAllAttributes();
        $assetVendors = $this->assetService->getAssetVendors();
        $allAssetTypes = $this->assetService->getAllAccessTypes();
        $allAssetVendors = $this->assetService->getAllAssetVendors();

        return view('assets.index', compact('users', 'list', 'assetTypes', 'attributes', 'assetVendors', 'allAssetTypes', 'allAssetVendors'));
    }

    /**
     *  returns user leave report to the ajax call.
     *
     * @param  mixed  $request
     * @return void
     */
    public function searchAsset(Request $request)
    {
        $draw = $request->get('draw');
        $totalRecords = $this->assetService->getAssetsCount();
        $totalRecordswithFilter = $this->assetService->getAssetsCountWithFilter($request);
        $assetData = $this->assetService->getAllAssets($request);
        $assets = $this->assetService->formatAssets($assetData['assets']);
        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $assets,
            'filteredAssetValue' => $assetData['assetValue'],
            'totalAssetDepreciatedValue' => $assetData['assetDepreciatedValue'],
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate(
            [
                'asset_type_id' => 'required',
                'asset_name' => 'required',
                'asset_value' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/', 'numeric'],
                'serial_number' => [
                    'nullable',
                    Rule::unique('assets', 'serial_number')->whereNull('deleted_at'),
                ],
                'status' => [
                    'required',
                    function ($attribute, $status, $fail) use ($request) {
                        if ($status == 'allocated') {
                            if (! $request->input('user_id')) {
                                $fail('User is required');

                                return;
                            }
                            if (! $request->input('assigned_date')) {
                                $fail('Assigned date is required');

                                return;
                            }
                            if ($request->input('purchased_date')) {
                                $purchaseDate = Carbon::createFromFormat('d/m/Y', $request->input('purchased_date'));
                                $assignedDate = Carbon::createFromFormat('d/m/Y', $request->input('assigned_date'));

                                if ($purchaseDate->greaterThan($assignedDate)) {
                                    $fail('The assigned date must be greater than the purchase date '.$purchaseDate->format('d/m/Y'));

                                    return;
                                }
                            }
                        }
                    },
                ],
                'files.*' => 'mimes:pdf,jpg,png,jpeg|max:5120'
            ]
        );

        $this->assetService->store($request);

        $assets = $this->assetService->getAssetsForStore($this->pagination);

        $users = $this->getUserNotClients();

        $content = view('assets.list', compact('users', 'assets'))->render();

        $res = [
            'status' => 'Saved',
            'message' => 'Asset has been created successfully',
            'data' => $content
        ];

        return response()->json($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $asset = $this->assetService->getAsset($id);

        return view('assets.view', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $asset = $this->assetService->getAssetForEdit($id);
        $users = $this->getUserNotClients();
        $assetTypes = $this->assetService->getAssetTypes();
        $assetVendors = $this->assetService->getAssetVendors();
        $assetTypeAttributes = $this->assetTypeService->getAttributesForAssetType($asset->asset_type_id);
        $assetAttributeValues = $this->assetService->getAttributeValuesForAsset($asset->id)->pluck('attribute_value_id')->toArray();

        return view('assets.edit', compact('asset', 'users', 'assetTypes', 'assetVendors', 'assetTypeAttributes', 'assetAttributeValues'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $files = [];
        $request->validate([
            'asset_type_id' => 'required',
            'asset_name' => 'required',
            'asset_value' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/', 'numeric'],
            'serial_number' => [
                'nullable',
                Rule::unique('assets', 'serial_number')->whereNull('deleted_at')->ignore($id),
            ],
            'status' => [
                'required',
                function ($attribute, $status, $fail) use ($id, $request) {
                    if ($status == 'allocated') {
                        if (! $request->input('user_id')) {
                            $fail('User is required');

                            return;
                        }
                        if (! $request->input('assigned_date')) {
                            $fail('Assigned date is required');

                            return;
                        }

                        $asset = $this->findAssetById($id);
                        if (! $asset) {
                            $fail('Asset with the provided ID not found.');

                            return;
                        }

                        // $purchaseDate = $asset->purchased_date;

                        // $purchaseDate = Carbon::parse($purchaseDate);
                        if ($request->input('purchased_date')) {
                            $purchaseDate = Carbon::createFromFormat('d/m/Y', $request->input('purchased_date'));
                            $assignedDate = Carbon::createFromFormat('d/m/Y', $request->input('assigned_date'));

                            if ($purchaseDate->greaterThan($assignedDate)) {
                                $fail('The assigned date must be greater than the purchase date '.$purchaseDate->format('d/m/Y'));

                                return;
                            }
                        }
                    }
                },
            ],
            'files.*' => 'mimes:pdf,jpg,png,jpeg|max:5120'
        ]);

        $this->assetService->update($request, $id);

        $users = $this->getUserNotClients();
        $assets = $this->assetService->getAssetsForStore($this->pagination);

        $content = view('assets.list', compact('users', 'assets'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Asset details has been updated successfully',
            'data' => $content
        ];

        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset = $this->findAssetById($id);
        if (in_array($asset->status, config('general.assets.status.has-users'))) {
            $res = [
                'status' => 'error',
                'message' => 'Cannot delete this asset as there are user associated with this asset.',
            ];
        } else {
            $this->deleteAsset($id);
            $res = [
                'status' => 'success',
                'message' => 'Asset has been deleted successfully',
            ];
        }

        return response()->json($res);
    }

    public function deleteDoc($id)
    {
        $this->deleteAssetDocument($id);

        $res = [
            'status' => 'success',
            'message' => 'Document has been Deleted successfully',
        ];

        return response()->json($res);
    }

    public function uploadAssetFiles(Request $request)
    {
        $validator = $request->validate(
            [
                'file' => 'mimes:pdf,jpg,png,jpeg|max:5120'
            ]
        );

        $file = request('file');
        $docName = time().$file->getClientOriginalName();

        $this->createAssetDocument([
            'asset_id' => request('asset_id'),
            'path' => $file->storeAs('assets/documents', $docName)
        ]);
    }

    public function getDocuments()
    {
        $asset = $this->assetService->getAllAssetsForId();

        $content = view('assets.documents', compact('asset'))->render();

        $res = [
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function exportExcelAssets()
    {
        return $this->assetService->exportExcelAssets();
    }
}
