<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use App\Traits\GeneralTrait;
use  Illuminate\Http\Request;

class SessionTypeController extends Controller
{
    use GeneralTrait;

    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        $types = $this->sessionTypes();

        return view('settings.session-types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        $data = [
            'title' => request('title'),
            'slug' => strtolower(request('title')),
            'status' => 1
        ];
        $this->createSessionType($data);
        $types = $this->sessionTypes();

        $content = view('settings.session-types.list', compact('types'))->render();

        $res = [
            'status' => 'success',
            'data' => $content
        ];

        return response()->json($res);
    }

    public function destroy($id)
    {
        $this->deleteSessionType($id);
        $types = $this->sessionTypes();
        $content = view('settings.session-types.list', compact('types'))->render();

        $res = [
            'status' => 'success',
            'data' => $content
        ];

        return response()->json($res);
    }

    public function edit($id)
    {
        $type = $this->getSessionTypeById($id);

        return view('settings.session-types.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'edit_title' => 'required',
        ]);

        $data = [
            'title' => request('edit_title'),
            'slug' => strtolower(request('edit_title')),
            'status' => 1
        ];

        $this->updateSessionType($id, $data);
        $types = $this->sessionTypes();

        $content = view('settings.session-types.list', compact('types'))->render();

        $res = [
            'status' => 'success',
            'data' => $content
        ];

        return response()->json($res);
    }
}
