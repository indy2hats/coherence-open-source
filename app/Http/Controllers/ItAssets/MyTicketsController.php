<?php

namespace App\Http\Controllers\ItAssets;

use App\Http\Controllers\Controller;
use App\Services\AssetTicketService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class MyTicketsController extends Controller
{
    use GeneralTrait;

    public $pagination;
    public $assetTicketService;

    public function __construct(AssetTicketService $assetTicketService)
    {
        $this->pagination = config('general.pagination');
        $this->assetTicketService = $assetTicketService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeeTicketRaisedAssetList(Request $request)
    {
        $assets = $this->assetTicketService->getAssetTickets($this->getCurrentUserId(), $this->pagination);

        return view('assets.employee-ticket-raised-list', compact('assets'));
    }

    /**
     * Get asset search parameters.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchEmployeeTicketAsset()
    {
        $assets = $this->assetTicketService->getAssetTicketsForSearchEmployee($this->getCurrentUserId(), $this->pagination);

        return view('assets.employee-ticket-raised-list', compact('assets'));
    }
}
