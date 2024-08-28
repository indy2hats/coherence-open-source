<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $estimated_time = $this->faker->numberBetween(1, 40);

        return [
            'project_id' => Project::NotArchived()->inRandomOrder()->first()->id,
            'code' => $this->faker->unique()->numerify('T-##'),
            'title' => $this->faker->unique()->numerify('Task ##'),
            'priority' => $this->faker->randomElement(['High', 'Medium', 'Low']),
            'estimated_time' => $estimated_time,
            'actual_estimated_time' => $this->faker->numberBetween($estimated_time + 1, $estimated_time + 6),
            'description' => $this->faker->paragraph(1),
            'is_archived' => 0,
            'status' => 'Backlog',
            'created_by' => User::active()->whereIn('role_id', [1, 2, 6])->inRandomOrder()->first()->id,
            'add_to_board' => 1
        ];
    }
}
