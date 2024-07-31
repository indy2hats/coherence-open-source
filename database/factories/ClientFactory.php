<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'company_name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'city' => $this->faker->city,
            'post_code' => $this->faker->postcode,
            'country' => $this->faker->country,
            'state' => $this->faker->state,
            'currency' => config('seeder-faker.client.currency'),
        ];
    }
}
