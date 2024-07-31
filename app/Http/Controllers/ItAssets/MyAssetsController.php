<?php

namespace App\Http\Controllers\ItAssets;

use App\Http\Controllers\Controller;
use App\Services\AssetService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class MyAssetsController extends Controller
{
    use GeneralTrait;

    public $pagination;
    public $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->pagination = config('general.pagination');
        $this->assetService = $assetService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeeAssetList(Request $request)
    {
        $assets = $this->assetService->getAssetsForEmployeeAssetList($this->getCurrentUserId(), $this->pagination);

        return view('assets.employee-list', compact('assets'));
    }

    /**
     * Return asset.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function returnAsset($id)
    {
        $asset = $this->findAssetUserById($id);
        if ($asset) {
            $this->assetService->updateAssetUserWhere($id, [
                'status' => 'inactive'
            ]);

            $this->assetService->updateAssetWhere($asset->asset_id, [
                'status' => 'non_allocated'
            ]);

            $res = [
                'status' => 'success',
                'message' => 'Asset has been returned successfully',
            ];
        } else {
            $res = [
                'status' => 'error',
                'message' => 'Something went wrong! try again later',
            ];
        }

        return response()->json($res);
    }

    /**
     * raise aticket for an asset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ticketRaiseAsset(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'reason' => 'required',
            'id' => 'required'
        ]);

        $asset = $this->assetService->getAssetUserWhere(request('id'));
        $this->createAssetTicket([
            'user_id' => $asset->user_id,
            'asset_id' => $asset->asset_id,
            'asset_user_id' => request('id'),
            'type' => request('type'),
            'issue' => request('reason')
        ]);
        $this->assetService->updateAssetUserWhere(request('id'), [
            'status' => 'ticket_raised']);

        $this->assetService->updateAssetWhere($asset->asset_id, [
            'status' => 'ticket_raised'
        ]);

        $res = [
            'status' => 'success',
            'message' => 'Ticket has been raised successfully.
            '
        ];

        return response()->json($res);
    }
}
