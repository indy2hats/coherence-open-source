<?php

namespace App\Http\Controllers\ItAssets;

use App\Http\Controllers\Controller;
use App\Services\AssetVendorService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorsController extends Controller
{
    use GeneralTrait;

    public $pagination;
    protected $assetVendorService;

    public function __construct(AssetVendorService $assetVendorService)
    {
        $this->pagination = config('general.pagination');
        $this->assetVendorService = $assetVendorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $assetVendors = $this->assetVendorService->getAssetVendors($this->pagination);

        return view('assets.asset-vendors.index', compact('assetVendors'));
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
            'name' => ['required', Rule::unique('asset_vendors', 'name')
            ->whereNull('deleted_at')],
            'phone' => 'regex:/^\d{10}$/',
            'email' => 'nullable|email',
        ]);

        $data = [
            'name' => request('name'),
            'phone' => request('phone'),
            'email' => request('email'),
            'description' => request('description'),
        ];

        $this->createAssetVendor($data);

        $assetVendors = $this->assetVendorService->getAssetVendors($this->pagination);
        $content = view('assets.asset-vendors.list', compact('assetVendors'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Vendor has been created successfully',
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
        $assetVendor = $this->assetVendorService->assetVendorWhere($id);

        return view('assets.asset-vendors.edit', compact('assetVendor'));
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
            'name' => ['required', Rule::unique('asset_vendors', 'name')
            ->whereNull('deleted_at')->ignore($id)],
            'phone' => 'regex:/^\d{10}$/',
            'email' => 'nullable|email',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);

        $data = [
            'name' => request('name'),
            'phone' => request('phone'),
            'email' => request('email'),
            'description' => request('description'),
            'status' => request('status')
        ];

        $this->updateAssetVendor($id, $data);

        $assetVendors = $this->assetVendorService->getAssetVendors($this->pagination);
        $content = view('assets.asset-vendors.list', compact('assetVendors'))->render();
        $res = [
            'status' => 'ok',
            'message' => 'Vendor details has been updated successfully',
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
        if ($this->assetVendorService->vendorAssetsCount($id) > 0) {
            $res = [
                'status' => 'error',
                'message' => 'Cannot delete this vendor as there are assets associated with this vendor.',
            ];
        } else {
            $this->deleteAssetVendor($id);
            $res = [
                'status' => 'success',
                'message' => 'Vendor has been deleted successfully',
            ];
        }

        return response()->json($res);
    }
}
