<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class ShowDailyStatusReportPageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'show_daily_status_report_page' => [
                'label' => 'Enable Daily Status Report',
                'slug' => 'show_daily_status_report_page',
                'value' => 1
            ]
        ];
        foreach ($settings as $detailItem) {
            Settings::create([
                'label' => $detailItem['label'],
                'slug' => $detailItem['slug'],
                'value' => $detailItem['value']
            ]);
        }
    }
}
