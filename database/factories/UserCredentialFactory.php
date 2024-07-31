<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserCredential;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCredentialFactory extends Factory
{
    protected $model = UserCredential::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = Carbon::now()->subDays(rand(1, 7));

        return [
            'user_id' => User::active()->inRandomOrder()->first()->id,
            'title' => $this->faker->company,
            // 'content' => $this->faker->paragraph(1),
            'created_at' => $date,
            'updated_at' => $date
        ];
    }
}
