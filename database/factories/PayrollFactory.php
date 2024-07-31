<?php

namespace Database\Factories;

use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollFactory extends Factory
{
    protected $model = Payroll::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payroll_date' => Carbon::now()->subMonths(rand(1, 4))->format('Y-m-01'),
            'total_amount' => $this->faker->unique()->numberBetween($min = 5000, $max = 200000),
            'incentives' => $this->faker->unique()->numberBetween($min = 1000, $max = 20000),
            'status' => 'complete'
        ];
    }
}
