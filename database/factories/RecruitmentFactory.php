<?php

namespace Database\Factories;

use App\Models\Recruitment;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecruitmentFactory extends Factory
{
    protected $model = Recruitment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->firstName(),
            'email' => $this->faker->unique()->email,
            'phone' => $this->faker->unique()->phoneNumber,
            'category' => $this->faker->randomElement(config('seeder-config.recruitment.category')),
            'status' => $this->faker->randomElement(config('seeder-config.recruitment.status')),
            'source' => $this->faker->randomElement(config('seeder-config.recruitment.source')),
            'resume' => 'resumes/Sample.pdf'
        ];
    }
}
