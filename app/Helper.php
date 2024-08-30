<?php

namespace App\Helpers;

use App\Models\Settings;

class Helper
{
    public static function getCompanyLogo()
    {
        $companyLogo = Settings::where('slug', 'company_logo')->first();

        return $companyLogo->value ? 'storage/'.$companyLogo->value : 'images/default-logo.png';
    }

    public static function showDailyStatusReportPage(): bool
    {
        return Settings::where('slug', 'show_daily_status_report_page')->value('value');
    }

    public static function getProjectView()
    {
        $projectView = Settings::where('slug', 'project_kanban_view')->first();

        return $projectView->value == '1' ? '/agile-board/' : '/projects/';
    }
}
