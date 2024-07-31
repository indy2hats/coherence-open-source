<?php

namespace Database\Factories;

use App\Models\QaIssue;
use Illuminate\Database\Eloquent\Factories\Factory;

class QaIssueFactory extends Factory
{
    protected $model = QaIssue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => ucwords($this->faker->unique()->word)
        ];
    }
}
