<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class EmailConfigUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emailDetails = [
            'hour_tracker_email_recipients' => 'email_config_employee_hour_email_recipients',
            'below_40hour_email_recipients' => 'email_config_weekly_low_hours_email_recipients',
            'min_hours_per_day' => 'email_config_min_hours_per_day',
            'daily_mail_excluded_departments' => 'email_config_daily_mail_excluded_departments',
            'weekly_report_cron_day' => 'email_config_weekly_report_cron_day'
        ];
        foreach ($emailDetails as $keySlug => $slug) {
            Settings::where('slug', $keySlug)->update(['slug' => $slug]);
        }
    }
}
