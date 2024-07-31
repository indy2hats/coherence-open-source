<?php

namespace Database\Seeders;

use App\Models\Recruitment;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class RecruitmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Recruitment::factory()->count(1)->create()->each(function ($candidate, $index) {
            Schedule::factory()->count(1)->create([
                'recruitment_id' => $candidate->id,
                'machine_test2' => null,
                'technical_interview' => null,
                'hr_interview' => null,
                'machine_test1_status' => 'Scheduled'
            ]);
        });
    }
}
