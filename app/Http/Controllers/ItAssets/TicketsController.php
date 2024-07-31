<?php

namespace App\Http\Controllers\ItAssets;

use App\Http\Controllers\Controller;
use App\Services\AssetService;
use App\Services\AssetTicketService;
use App\Services\AssetTicketStatusService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    use GeneralTrait;

    public $pagination;
    protected $assetTicketService;
    protected $assetService;
    protected $assetTicketStatusService;

    public function __construct(AssetTicketService $assetTicketService, AssetService $assetService, AssetTicketStatusService $assetTicketStatusService)
    {
        $this->pagination = config('general.pagination');
        $this->assetTicketService = $assetTicketService;
        $this->assetService = $assetService;
        $this->assetTicketStatusService = $assetTicketStatusService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ticketRaisedAssetList(Request $request)
    {
        $assets = $this->assetTicketService->assetsForTicketRaisedAssetList($this->pagination);
        $users = $this->getUserNotClients();
        $list = $this->assetService->getList();
        $ticketStatus = $this->assetTicketStatusService->getTicketStatus();

        return view('assets.ticket-raise-index', compact('assets', 'users', 'list', 'ticketStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ticketStatusEdit($id)
    {
        $ticket = $this->assetTicketService->getAssetTicketWhere($id);
        $ticketStatus = $this->assetTicketStatusService->getTicketStatus();

        return view('assets.status-update', compact('ticket', 'ticketStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ticketStatusUpdate(Request $request)
    {
        $request->validate([
            'status_id' => 'required',
            'id' => 'required'
        ]);

        $data = [
            'status_id' => request('status_id')
        ];

        $assetTicket = $this->assetTicketService->getAssetTicketWhere(request('id'));
        if ($this->assetTicketStatusService->getAssetTicketStatusWhere(request('status_id'))->is_close_issue == 'yes') {
            $data += ['status' => 'closed'];
            if ($this->assetTicketStatusService->getAssetTicketStatusWhere(request('status_id'))->is_allocate_asset == 'yes') {
                $this->assetService->updateAssetUserWhere($assetTicket->asset_user_id, [
                    'status' => 'allocated'
                ]);
                $this->assetService->updateAssetWhere($assetTicket->asset_id, [
                    'status' => 'allocated'
                ]);
            }
        }
        $this->assetTicketService->updateAssetTicketWhere(request('id'), $data);

        if ($this->assetTicketStatusService->getAssetTicketStatusWhere(request('status_id'))->is_inactive_asset == 'yes') {
            $this->assetService->updateAssetUserWhere($assetTicket->asset_user_id, [
                'status' => 'inactive'
            ]);

            $this->assetService->updateAssetWhere($assetTicket->asset_id, [
                'status' => 'inactive'
            ]);
        }

        $res = [
            'status' => 'ok',
            'message' => 'Ticket details have been successfully updated'
        ];

        return response()->json($res);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ticketIssueUpdate($id)
    {
        $asset = $this->assetTicketService->getAssetTicketWhere($id);

        return view('assets.ticket-raise-edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function issueUpdate(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'reason' => 'required',
            'id' => 'required'
        ]);

        $data = [
            'type' => request('type'),
            'issue' => request('reason')
        ];

        $this->updateAssetTicket(request('id'), $data);

        $assets = $this->assetTicketService->getAssetTicketsForIssueUpdate($this->getCurrentUserId(), $this->pagination);

        $content = view('assets.employee-ticket-raised-list', compact('assets'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Ticket details have been successfully updated.',
            'data' => $content
        ];

        return response()->json($res);
    }

    /**
     * Get asset search parameters.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchTicketAsset()
    {
        $assets = $this->assetTicketService->getAssetTicketsForSearch($this->pagination);
        $users = $this->getUserNotClients();
        $list = $this->assetService->getList();
        $ticketStatus = $this->assetTicketStatusService->getTicketStatus();

        return view('assets.ticket-raise-index', compact('assets', 'users', 'list', 'ticketStatus'));
    }
}
