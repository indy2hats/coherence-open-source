<?php

namespace App\Http\Controllers\Miscellaneous;

use App\Http\Controllers\Controller;
use App\Services\AlertService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ManagePopupAlertController extends Controller
{
    use GeneralTrait;

    private $alertService;

    public function __construct(AlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = $this->getUserWishList();

        return view('alerts.index', compact('list'));
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
            'date' => 'required|unique:user_wishes,date',
            'title' => 'required',
            'type' => 'required',
            'file' => 'required'
        ]);

        if (request('type') != 'Text') {
            $request->validate([
                'file' => 'mimes:jpeg,png,bmp,gif,svg,mp4,mpeg,avi',
            ]);
        }

        $list = $this->alertService->storeAndGetUserWishList();
        $content = view('alerts.list', compact('list'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Alert created successfully',
            'data' => $content,
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
        $list = $this->alertService->deleteAndGetUserWishList($id);
        $content = view('alerts.list', compact('list'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Alert deleted successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }
}
