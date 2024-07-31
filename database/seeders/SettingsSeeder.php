<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Settings::truncate();
        Schema::enableForeignKeyConstraints();

        $companyDetails = config('seeder-faker.settings.config');

        foreach ($companyDetails as $detailItem) {
            Settings::create([
                'label' => $detailItem['label'],
                'slug' => $detailItem['slug'],
                'value' => $detailItem['value'],
            ]);
        }
    }
}
