<?php

namespace Database\Factories;

use App\Models\Recruitment;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'recruitment_id' => Recruitment::inRandomOrder()->first()->id,
            'machine_test1' => Carbon::now(),
            'machine_test2' => Carbon::now()->addDays(rand(5, 10)),
            'technical_interview' => Carbon::now()->addDays(rand(10, 15)),
            'hr_interview' => Carbon::now()->addDays(rand(15, 20)),
            'technical_interview_status' => 'Not Done',
            'hr_interview_status' => 'Not Done',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }
}
