<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Services\SettingsService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ReportsSettingsController extends Controller
{
    use GeneralTrait;

    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        $settings = Settings::getEmailConfigSettings();
        $choosenData = $this->settingsService->getChoosenData($settings);
        $settings = $this->settingsService->getData($settings);
        $minDailyHours = ($settings['min_hours_per_day'] ? $settings['min_hours_per_day']->value : config('general.min-session-hour-per-day'));
        $minWeeklyHours = $this->settingsService->getWeeklyHours($minDailyHours);
        $roles = $this->getAllRoles();
        $departments = $this->getDepartments();
        $otherSettings = $this->settingsService->getSettings();

        return view('settings.reports.edit', compact('roles', 'departments', 'choosenData', 'settings', 'minWeeklyHours', 'otherSettings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'min_hours_per_day' => 'numeric|between:0,24|required',
            'weekly_report_cron_day' => 'required'
        ]);

        $this->settingsService->updateReportsSettings($request);
        $minWeeklyHours = $this->settingsService->getWeeklyHours($request->min_hours_per_day);

        $res = [
            'message' => 'Report Settings updated',
            'data' => $minWeeklyHours,
        ];

        return response()->json($res);
    }
}
