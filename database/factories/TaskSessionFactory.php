<?php

namespace Database\Factories;

use App\Models\TaskSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskSessionFactory extends Factory
{
    protected $model = TaskSession::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startTime = $this->faker->unique()->dateTimeBetween(Carbon::now()->subDays(4), Carbon::now());

        return [
            'current_status' => $this->faker->randomElement(['over']),
            'start_time' => $startTime->format('Y-m-d 09:45:12'),
            'end_time' => $startTime,
            'billed_today' => $this->faker->numberBetween($min = 3, $max = 2000),
            'comments' => $this->faker->paragraph(1),
            'total' => $this->faker->numberBetween($min = 3, $max = 2000),
            'session_type' => $this->faker->randomElement(['Development', 'Meeting']),
            'created_at' => $startTime,
            'updated_at' => $startTime
        ];
    }
}
