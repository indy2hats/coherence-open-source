<?php

namespace App\Http\Controllers\ItAssets;

use App\Http\Controllers\Controller;
use App\Services\AssetTypeService;
use App\Services\AttributeService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TypesController extends Controller
{
    use GeneralTrait;

    public $pagination;
    protected $assetTypeService;
    protected $attributeService;

    public function __construct(AssetTypeService $assetTypeService, AttributeService $attributeService)
    {
        $this->pagination = config('general.pagination');
        $this->assetTypeService = $assetTypeService;
        $this->attributeService = $attributeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $assetTypes = $this->assetTypeService->getAssetTypes($this->pagination);
        $attributes = $this->attributeService->getAllAttributes();

        return view('assets.asset-types.index', compact('assetTypes', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('asset_types', 'name')
            ->whereNull('deleted_at')],
            'depreciation_rate' => ['nullable', 'numeric', 'between:0,100'],
        ]);

        $data = [
            'name' => request('name'),
            'depreciation_rate' => request('depreciation_rate')
        ];

        $id = $this->createAssetType($data)->id;
        if ($request['attributes']) {
            $this->assetTypeService->tagAttributesToAssetType($id, $request['attributes']);
        }
        $assetTypes = $this->assetTypeService->getAssetTypes($this->pagination);

        $content = view('assets.asset-types.list', compact('assetTypes'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Asset type created successfully',
            'data' => $content
        ];

        return response()->json($res);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $assetType = $this->assetTypeService->getAssetTypeWhere($id);
        $assetTypeAttributes = $this->assetTypeService->getAttributesForAssetType($id);

        if (! empty($assetTypeAttributes)) {
            $assetTypeAttributes = $assetTypeAttributes->pluck('attribute_id')->toArray();
        } else {
            $assetTypeAttributes = [];
        }
        $attributes = $this->attributeService->getAllAttributes();

        return view('assets.asset-types.edit', compact('assetType', 'assetTypeAttributes', 'attributes'));
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
        $request->validate([
            'name' => ['required', Rule::unique('asset_types', 'name')
            ->whereNull('deleted_at')->ignore($id)],
            'depreciation_rate' => ['nullable', 'numeric', 'between:0,100'],
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);

        $data = [
            'name' => request('name'),
            'depreciation_rate' => request('depreciation_rate'),
            'status' => request('status')
        ];

        $this->updateAssetType($id, $data);
        $this->assetTypeService->tagAttributesToAssetType($id, $request['attributes']);

        $assetTypes = $this->assetTypeService->getAssetTypes($this->pagination);
        $content = view('assets.asset-types.list', compact('assetTypes'))->render();
        $res = [
            'status' => 'ok',
            'message' => 'Asset type details updated successfully',
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
        if ($this->assetTypeService->typeAssetsCount($id) > 0) {
            $res = [
                'status' => 'error',
                'message' => 'Cannot delete this type as there are assets associated with this type.',
            ];
        } else {
            $this->deleteAssestType($id);
            $res = [
                'status' => 'success',
                'message' => 'Asset type deleted successfully',
            ];
        }

        return response()->json($res);
    }

    public function getAssetTypeAttributes(Request $request)
    {
        $assetTypeAttributes = $this->assetTypeService->getAttributesForAssetType($request['assetTypeId']);

        $content = view('assets.asset-types.attributes', compact('assetTypeAttributes'))->render();

        $res = [
            'data' => $content
        ];

        return response()->json($res);
    }
}
