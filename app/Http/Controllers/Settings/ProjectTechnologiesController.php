<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectTechnologiesController extends Controller
{
    use GeneralTrait;

    public $pagination;
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->pagination = config('general.pagination');
        $this->settingsService = $settingsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $technologies = $this->settingsService->getTechnologies($this->pagination);

        return view('settings.technologies.index', compact('technologies'));
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
            'name' => ['required', Rule::unique('technologies', 'name')
            ->whereNull('deleted_at')]
        ]);

        $data = [
            'name' => request('name')
        ];

        $this->createTechnology($data);

        $technologies = $this->settingsService->getTechnologies($this->pagination);
        $content = view('settings.technologies.list', compact('technologies'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Technology created successfully',
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
        $technology = $this->getTechnologyById($id);

        return view('settings.technologies.edit', compact('technology'));
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
            'name' => ['required', Rule::unique('technologies', 'name')
            ->whereNull('deleted_at')->ignore($id)],
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);

        $this->settingsService->updateTechnologies($id);

        $technologies = $this->settingsService->getTechnologies($this->pagination);
        $content = view('settings.technologies.list', compact('technologies'))->render();

        $res = [
            'status' => 'success',
            'message' => 'Technology details updated successfully',
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
        $res = $this->settingsService->destroyTechnology($id);

        return response()->json($res);
    }
}
