<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class ProjectSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'project_kanban_view' => [
                'label' => 'Kanban View',
                'slug' => 'project_kanban_view',
                'value' => 0
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
