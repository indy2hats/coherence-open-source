<?php

namespace Database\Factories;

use App\Models\IssueCategory;
use App\Models\IssueRecord;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueRecordFactory extends Factory
{
    protected $model = IssueRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $issueDate = Carbon::now()->subDays(rand(5, 30));

        return [
            'project_id' => Project::NotArchived()->inRandomOrder()->first()->id,
            'added_by' => User::active()->whereIn('role_id', [3, 2, 6])->inRandomOrder()->first()->id,
            'category' => IssueCategory::inRandomOrder()->first()->id,
            'title' => $this->faker->name,
            'description' => $this->faker->paragraph(1),
            'solution' => $this->faker->paragraph(1),
            'created_at' => $issueDate,
            'updated_at' => $issueDate
        ];
    }
}
