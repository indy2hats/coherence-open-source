<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskWithTimerSeeder::productiveTaskStartedWithTimer();
        TaskWithTimerSeeder::upskillingTaskStartedWithTimer();
        TaskInProgressSeeder::taskOverDue();
    }
}
