<?php

namespace App\Http\Controllers\ItAssets;

use App\Http\Controllers\Controller;
use App\Services\AssetTicketService;
use App\Services\AssetTicketStatusService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TicketStatusController extends Controller
{
    use GeneralTrait;

    public $pagination;
    public $assetTicketStatusService;
    protected $assetTicketService;

    public function __construct(AssetTicketStatusService $assetTicketStatusService, AssetTicketService $assetTicketService)
    {
        $this->pagination = config('general.pagination');
        $this->assetTicketStatusService = $assetTicketStatusService;
        $this->assetTicketService = $assetTicketService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $assetTicketStatus = $this->assetTicketStatusService->getTicketStatusForIndex($this->pagination);

        return view('assets.ticket-status.index', compact('assetTicketStatus'));
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
            'name' => ['required', Rule::unique('asset_ticket_statuses', 'title')
            ->whereNull('deleted_at')],
            'description' => 'required'
        ]);

        $data = [
            'title' => request('name'),
            'description' => request('description'),
            'slug' => Str::slug(request('name')),
            'is_inactive_asset' => request('is_inactive_asset') == 'on' ? 'yes' : 'no',
            'is_close_issue' => request('is_close_issue') == 'on' ? 'yes' : 'no',
            'is_allocate_asset' => request('is_allocate_asset') == 'on' ? 'yes' : 'no'
        ];
        $this->createAssetTicketStatus($data);

        $assetTicketStatus = $this->assetTicketStatusService->getTicketStatusForIndex($this->pagination);
        $content = view('assets.ticket-status.list', compact('assetTicketStatus'))->render();

        $res = [
            'status' => 'Saved',
            'message' => 'Status has been created successfully',
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
        $assetTicketStatus = $this->assetTicketStatusService->getAssetTicketStatusWhere($id);

        return view('assets.ticket-status.edit', compact('assetTicketStatus'));
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
            'name' => ['required', Rule::unique('asset_ticket_statuses', 'title')
            ->whereNull('deleted_at')->ignore($id)],
            'description' => 'required'
        ]);

        $data = [
            'title' => request('name'),
            'description' => request('description'),
            'slug' => Str::slug(request('name')),
            'is_inactive_asset' => request('is_inactive_asset') == 'on' ? 'yes' : 'no',
            'is_close_issue' => request('is_close_issue') == 'on' ? 'yes' : 'no',
            'is_allocate_asset' => request('is_allocate_asset') == 'on' ? 'yes' : 'no'
        ];
        $this->updateAssetTicketStatus($id, $data);

        $assetTicketStatus = $this->assetTicketStatusService->getTicketStatusForIndex($this->pagination);
        $content = view('assets.ticket-status.list', compact('assetTicketStatus'))->render();
        $res = [
            'status' => 'ok',
            'message' => 'Status details has been updated successfully',
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
        if ($this->assetTicketService->statusTicketsCount($id) > 0) {
            $res = [
                'status' => 'error',
                'message' => 'Cannot delete this status as there are tickets associated with this status.',
            ];
        } else {
            $this->deleteAssetTicketStatus($id);
            $res = [
                'status' => 'success',
                'message' => 'Status has been deleted successfully',
            ];
        }

        return response()->json($res);
    }
}
