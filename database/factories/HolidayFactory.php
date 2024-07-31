<?php

namespace Database\Factories;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class HolidayFactory extends Factory
{
    protected $model = Holiday::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'holiday_date' => Carbon::now()->addDays(rand(5, 180))->format('Y-m-d'),
            'holiday_name' => ucwords($this->faker->unique()->word)
        ];
    }
}
