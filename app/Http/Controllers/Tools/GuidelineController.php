<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\ToolService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class GuidelineController extends Controller
{
    use GeneralTrait;

    private $toolService;

    public function __construct(ToolService $toolService)
    {
        $this->toolService = $toolService;
    }

    public function index()
    {
        $list = $this->toolService->createDataSet();
        $data = $this->toolService->getTaxonomyList();

        return view('guidelines.index', compact('list', 'data'));
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
            'content' => 'required'
        ]);

        $this->toolService->createGuideLine();
        $list = $this->toolService->createDataSet();

        $content = view('guidelines.list', compact('list'))->render();
        $data = $this->toolService->getTaxonomyList();
        $search = view('guidelines.search', compact('data'))->render();

        $respose = [
            'status' => 'ok',
            'message' => 'Guideline Added Successfully',
            'data' => $content,
            'search' => $search
        ];

        return response()->json($respose);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = $this->findGuideLine($id);

        return view('guidelines.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->findGuideLine($id);
        $data = $this->toolService->getTaxonomyList();

        return view('guidelines.edit', compact('item', 'data'));
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
            'content' => 'required'
        ]);

        $this->toolService->updateGuideline($id);
        $list = $this->toolService->createDataSet();
        $content = view('guidelines.list', compact('list'))->render();
        $data = $this->toolService->getTaxonomyList();
        $search = view('guidelines.search', compact('data'))->render();

        $respose = [
            'status' => 'ok',
            'message' => 'Guideline Updated Successfully',
            'data' => $content,
            'search' => $search
        ];

        return response()->json($respose);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->deleteGuideline($id);
        $list = $this->toolService->createDataSet();

        $content = view('guidelines.list', compact('list'))->render();

        $respose = [
            'status' => 'ok',
            'message' => 'Guideline Deleted Successfully',
            'data' => $content,
        ];

        return response()->json($respose);
    }

    public function loadGuideline()
    {
        $item = $this->getGuideline();
        $content = view('guidelines.details', compact('item'))->render();

        $respose = [
            'status' => 'ok',
            'data' => $content,
        ];

        return response()->json($respose);
    }

    public function getList()
    {
        $data = $this->toolService->getTaxonomyList();

        return response()->json(['data' => $data]);
    }

    public function getCategoryList()
    {
        $list = $this->toolService->createDataSet(request('type'));

        $content = view('guidelines.list', compact('list'))->render();

        $respose = [
            'status' => 'ok',
            'data' => $content,
        ];

        return response()->json($respose);
    }

    public function addTag(Request $request)
    {
        $request->validate(['category' => 'required']);

        $this->toolService->addTag();
        $data = $this->toolService->getTaxonomyList();

        $content = view('guidelines.search', compact('data'))->render();

        $respose = [
            'status' => 'ok',
            'data' => $content,
        ];

        return response()->json($respose);
    }
}
