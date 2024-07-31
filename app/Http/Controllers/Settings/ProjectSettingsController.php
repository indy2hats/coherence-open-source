<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use  App\Services\SettingsService;
use App\Traits\GeneralTrait;

class ProjectSettingsController extends Controller
{
    use GeneralTrait;

    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        $settings = Settings::getProjectSettings()->toArray();
        $roles = $this->getAllRoles();
        $projectChoosenData = $this->settingsService->getProjectChoosenData($settings);

        return view('settings.projects.edit', compact('settings', 'roles', 'projectChoosenData'));
    }

    public function update()
    {
        $this->settingsService->updateProjectSettings();

        return response()->json(['message' => 'Project settings updated']);
    }
}
