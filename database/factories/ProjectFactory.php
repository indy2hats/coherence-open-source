<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = Carbon::now()->subDays(rand(10, 20));

        return [
            'project_name' => $this->faker->company,
            'client_id' => Client::inRandomOrder()->first()->id,
            'project_id' => $this->faker->unique()->numerify('P-###'),
            'project_type' => $this->faker->randomElement(['PHP', 'Wordpress', 'Mobile App']),
            'start_date' => $date,
            'end_date' => Carbon::now()->addDays(rand(10, 20)),
            'cost_type' => $this->faker->randomElement(['Hourly', 'Fixed']),
            'rate' => $this->faker->numberBetween($min = 1000, $max = 10000),
            'estimated_hours' => $this->faker->numberBetween($min = 1000, $max = 10000),
            'priority' => $this->faker->randomElement(['High', 'Medium', 'Low']),
            'category' => $this->faker->randomElement(['Internal', 'External']),
            'description' => $this->faker->paragraph(1),
            'status' => 'Active',
            'is_archived' => 0,
            'deleted_at' => null,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
