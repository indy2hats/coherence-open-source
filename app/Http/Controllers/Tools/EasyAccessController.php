<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\ToolService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class EasyAccessController extends Controller
{
    use GeneralTrait;

    private $toolService;

    public function __construct(ToolService $toolService)
    {
        $this->toolService = $toolService;
    }

    public function addEasyAccess(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'link' => 'required'
        ]);

        $list = $this->toolService->addEasyAccess($request);

        $content = view('easy-access.list', compact('list'))->render();
        $links = view('partials.easy-access')->render();

        $data = [
            'status' => true,
            'data' => $content,
            'links' => $links
        ];

        return response()->json($data);
    }

    public function deleteEasyAccess()
    {
        $list = $this->toolService->deleteEasyAccess();
        $content = view('easy-access.list', compact('list'))->render();
        $links = view('partials.easy-access')->render();

        $data = [
            'status' => true,
            'data' => $content,
            'links' => $links
        ];

        return response()->json($data);
    }

    public function editEasyAccess(Request $request)
    {
        $request->validate([
            'edit_name' => 'required',
            'edit_link' => 'required'
        ]);

        $list = $this->toolService->editEasyAccess();

        $content = view('easy-access.list', compact('list'))->render();

        $links = view('partials.easy-access')->render();

        $data = [
            'status' => true,
            'data' => $content,
            'links' => $links
        ];

        return response()->json($data);
    }

    public function easyAccess()
    {
        $list = unserialize($this->getCurrentUser()->easy_access);

        return view('easy-access.index', compact('list'));
    }
}
