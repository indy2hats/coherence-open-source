<?php

namespace Database\Factories;

use App\Models\DailyStatusReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyStatusReportFactory extends Factory
{
    protected $model = DailyStatusReport::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = Carbon::now()->subDays(rand(1, 30));

        return [
            'user_id' => User::active()->whereIn('role_id', [2, 3, 6])->inRandomOrder()->first(),
            'added_on' => $date->format('Y-m-d'),
            'todays_task' => $this->faker->paragraph(1),
            'impediments' => $this->faker->paragraph(1),
            'tommorows_task' => $this->faker->paragraph(1),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
