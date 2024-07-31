<?php

namespace App\Http\Controllers;

use App\Models\TaskStatusType;
use Illuminate\Http\Request;

class TaskStatusTypeController extends Controller
{
    public function index()
    {
        $types = TaskStatusType::orderBy('order', 'ASC')->get();

        return view('settings.status-types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'order' => 'required'
        ]);

        $data = [
            'title' => request('title'),
            'order' => request('order')
        ];

        $exist = TaskStatusType::where('order', request('order'))->first();

        if ($exist) {
            TaskStatusType::where('order', '>=', request('order'))->increment('order');
        }

        TaskStatusType::create($data);

        $types = TaskStatusType::orderBy('order', 'ASC')->get();

        $content = view('settings.status-types.list', compact('types'))->render();

        $res = [
            'status' => 'success',
            'data' => $content
        ];

        return response()->json($res);
    }

    public function destroy($id)
    {
        TaskStatusType::find($id)->delete();

        $types = TaskStatusType::orderBy('order', 'ASC')->get();

        $content = view('settings.status-types.list', compact('types'))->render();

        $res = [
            'status' => 'success',
            'data' => $content
        ];

        return response()->json($res);
    }

    public function edit($id)
    {
        $type = TaskStatusType::find($id);

        return view('settings.status-types.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'edit_title' => 'required',
            'edit_order' => 'required',
        ]);

        TaskStatusType::find($id)->delete();

        $data = [
            'title' => request('edit_title'),
            'order' => request('edit_order')
        ];

        $exist = TaskStatusType::where('order', request('edit_order'))->first();

        if ($exist) {
            TaskStatusType::where('order', '>=', request('edit_order'))->increment('order');
        }

        TaskStatusType::create($data);

        $types = TaskStatusType::orderBy('order', 'ASC')->get();

        $content = view('settings.status-types.list', compact('types'))->render();

        $res = [
            'status' => 'success',
            'data' => $content
        ];

        return response()->json($res);
    }
}
