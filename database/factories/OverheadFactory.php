<?php

namespace Database\Factories;

use App\Models\Overhead;
use Illuminate\Database\Eloquent\Factories\Factory;

class OverheadFactory extends Factory
{
    protected $model = Overhead::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => date('Y-m-d'),
            'type' => $this->faker->randomElement(['Rent', 'Power charges', 'A/c', 'Operational']),
            'amount' => $this->faker->numberBetween($min = 12000, $max = 100000),
            'description' => $this->faker->paragraph(1),
        ];
    }
}
