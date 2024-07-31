<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $departmentList = [
            'Maintenance',
            'Client Management',
            'Planning and Construction',
            'Public Safety',
            'Sales and Marketing',
            'Business Development',
            'Software Department',
            'Marketing',
            'Operations',
            'Purchase',
            'Health Science',
            \Faker\Provider\fr_FR\Address::departmentName()
        ];

        return [
            'name' => $this->faker->unique()->randomElement($departmentList)
        ];
    }
}
