<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\ToolService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class MyCredentialsController extends Controller
{
    use GeneralTrait;

    private $toolService;

    public function __construct(ToolService $toolService)
    {
        $this->toolService = $toolService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $credentials = $this->getUserCredentials($this->getCurrentUserId());

        return view('credentials.index', compact('credentials'));
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
            'title' => 'required',
            'content' => 'required',
        ]);

        $this->toolService->createUserCredentials($request);
        $credentials = $this->getUserCredentials($this->getCurrentUserId());

        $content = view('credentials.list', compact('credentials'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Credentials Added Successfully',
            'data' => $content,
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
        $item = $this->toolService->getUserCredentials($id);

        return view('credentials.edit', compact('item'));
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
            'title' => 'required',
            'content' => 'required',
        ]);

        $this->toolService->updateUserCredentials($request, $id);
        $credentials = $this->getUserCredentials($this->getCurrentUserId());

        $content = view('credentials.list', compact('credentials'))->render();

        $res = [
            'status' => 'Saved',
            'message' => 'Credentials Updated Successfully',
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
        $this->deleteUserCredentials($id);
        $credentials = $this->getUserCredentials($this->getCurrentUserId());
        $content = view('credentials.list', compact('credentials'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Credentials Deleted Successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }
}
