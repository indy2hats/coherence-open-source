<?php

namespace App\Services;

use App\Models\WeekHoliday;
use App\Repository\SettingsRepository;
use App\Traits\GeneralTrait;
use Illuminate\Support\Str;

class SettingsService
{
    use GeneralTrait;

    protected $settingsRepository;

    private static $choosenData = ['employee_hour_email_recipients', 'daily_mail_excluded_departments', 'weekly_low_hours_email_recipients', 'weekly_report_cron_day', 'task_overdue_email_recipients'];
    private static $otherData = ['show_daily_status_report_page'];
    private static $projectChoosenData = ['project_show_task_actual_estimate'];

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public static function explodeCommaSeperatedData($data)
    {
        return ($data ?? null) ? explode(',', $data) : [];
    }

    public static function getChoosenData($settings)
    {
        $data = self::$choosenData;
        $choosen = [];
        foreach ($data as $item) {
            $itemCamelCase = Str::camel($item);

            $choosen[$itemCamelCase] = self::explodeCommaSeperatedData($settings[$item]['value']);
        }

        return $choosen;
    }

    public static function getData($settings)
    {
        foreach (self::$choosenData as $key) {
            if (isset($settings[$key])) {
                unset($settings[$key]);
            }
        }

        return $settings;
    }

    public function getSettings()
    {
        $settings = $this->settingsRepository->getData(self::$otherData);
        $otherSettings = [];
        foreach ($settings as $setting) {
            $otherSettings[$setting['slug']] = $setting;
        }

        return $otherSettings;
    }

    public function getWeeklyHours($minDailyHours)
    {
        $minWeeklyHours = $minDailyHours * $this->getNoOfBusinessDays();

        return $minWeeklyHours;
    }

    public function getNoOfBusinessDays()
    {
        return 7 - WeekHoliday::count();
    }

    public function storeCompanyInfo($request)
    {
        $input = request()->all();
        unset($input['_token']);

        foreach ($input as $slug => $data) {
            if ($slug == 'company_logo' && $request->hasFile($slug)) {
                $data = $request->file($slug)->store('companylogo');
            }
            $this->updateSettings($slug, $data);
        }
    }

    public function getTechnologies($pagination)
    {
        return $this->settingsRepository->getTechnologies($pagination);
    }

    public function updateTechnologies($id)
    {
        if ($this->settingsRepository->getProjectTechnologyCount($id) > 0) {
            $res = [
                'status' => 'error',
                'message' => 'Cannot update this technology as there are projects associated with it.'
            ];

            return response()->json($res);
        }

        $data = [
            'name' => request('name'),
            'status' => request('status')
        ];

        $this->updateTechnology($id, $data);
    }

    public function destroyTechnology($id)
    {
        if ($this->settingsRepository->getProjectTechnologyCount($id) > 0) {
            $res = [
                'status' => 'error',
                'message' => 'Cannot delete this technology as there are projects associated with it.',
            ];
        } else {
            $this->deleteTechnology($id);

            $res = [
                'status' => 'success',
                'message' => 'Technology deleted successfully',
            ];
        }

        return $res;
    }

    public function storeUserAccessLevel()
    {
        $roles = $this->roleNotAdministrator();
        $permissions = $this->permissions();

        foreach ($roles as $role) {
            foreach ($permissions as $permission) {
                $role->revokePermissionTo($permission);

                if (request(str_replace(' ', '_', $role->name).'-'.str_replace(' ', '_', $permission->name))) {
                    $role->givePermissionTo($permission);
                }
            }
        }

        return $this->getUserAccessList('User Access modified successfully');
    }

    public function getUserAccessList($message)
    {
        $roles = $this->roles();
        $permissions = $this->permissions();
        $content = view('settings.access-levels.list', compact('roles', 'permissions'))->render();

        return [
            'status' => 'Saved',
            'message' => $message,
            'data' => $content,
        ];
    }

    public function getBase()
    {
        return $this->settingsRepository->getBase();
    }

    public function changeCurrency()
    {
        $currency = $this->settingsRepository->changeCurrency();

        return $currency;
    }

    public function updateReportsSettings($request)
    {
        $choosenData = ['employeeHourEmailRecipients', 'weeklyLowHoursEmailRecipients', 'dailyMailExcludedDepartments', 'taskOverdueEmailRecipients'];
        $inputs = collect($request->except(['_token', '_method']));

        foreach ($choosenData as $key) {
            if (! $inputs->has($key)) {
                $inputs[$key] = null;
            }
        }

        $inputs = $inputs->map(function ($value, $key) use ($choosenData) {
            if (in_array($key, $choosenData)) {
                $value = is_array($value) ? implode(',', $value) : null;
            }

            return $value;
        })
        ->toArray();

        $showDailyStatusReportPage = $request->input('show_daily_status_report_page') ? 1 : 0;
        $this->updateSettings('show_daily_status_report_page', $showDailyStatusReportPage);

        foreach ($inputs as $slug => $data) {
            $slug = 'email_config_'.Str::snake($slug);
            $this->updateSettings($slug, $data);
        }
    }

    public function updateProjectSettings()
    {
        $projectChoosenData = self::$projectChoosenData;
        $inputs = request()->all();
        unset($inputs['_token'], $inputs['_method']);

        $inputs = collect($inputs)->map(function ($value, $key) use ($projectChoosenData) {
            if (in_array($key, $projectChoosenData)) {
                $value = is_array($value) ? implode(',', $value) : null;
            }

            return $value;
        })
        ->toArray();

        foreach ($inputs as $slug => $data) {
            $this->updateSettings($slug, $data);
        }
    }

    public static function getProjectChoosenData($settings)
    {
        $data = self::$projectChoosenData;
        $choosen = [];
        foreach ($data as $item) {
            $itemCamelCase = Str::camel($item);
            if (isset($settings[$item])) {
                $choosen[$itemCamelCase] = self::explodeCommaSeperatedData($settings[$item]['value']);
            }
        }

        return $choosen;
    }
}
