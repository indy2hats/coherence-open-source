<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $name = $this->faker->unique()->name($gender);

        return [
            'first_name' => $name,
            'last_name' => $this->faker->lastName,
            'password' => '3pw2d3w0!',
            'email' => strtolower(str_replace(' ', '', $name)).'@epmsdemo.com',
            'employee_id' => $this->faker->numerify('EMP-##'),
            'phone' => $this->faker->unique()->phoneNumber,
            'joining_date' => $this->faker->unique()->date($format = 'Y-m-d', $max = 'now'),
            'department_id' => $this->faker->numberBetween($min = 7, $max = 10),
            'designation_id' => 1,
            'role_id' => 3,
            'monthly_salary' => $this->faker->numberBetween($min = 20000, $max = 182000),
            'nick_name' => null,
            'status' => 1,
            'must_change_password' => 0,
            'wish_notify' => 1,
            'easy_access' => 'a:0:{}',
            'gender' => ucwords($gender),
        ];
    }
}
