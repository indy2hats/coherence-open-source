<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\ToolService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class WorkNotesController extends Controller
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
        $workNotes = $this->getWorkNotes($this->getCurrentUserId());

        return view('work-notes.index', compact('workNotes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->toolService->createWorkNote();
        $workNotes = $this->getWorkNotes($this->getCurrentUserId());
        $content = view('work-notes.list', compact('workNotes'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Work Note Created',
            'data' => $content,
        ];

        return response()->json($res);
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
        $this->toolService->updateWorkNote($id);
        $workNotes = $this->getWorkNotes($this->getCurrentUserId());
        $content = view('work-notes.list', compact('workNotes'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Work note Updated',
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
        $this->toolService->deleteWorkNote($id);
        $workNotes = $this->getWorkNotes($this->getCurrentUserId());

        $content = view('work-notes.list', compact('workNotes'))->render();

        $res = [
            'status' => 'ok',
            'message' => 'Work Note Deleted',
            'data' => $content,
        ];

        return response()->json($res);
    }
}
