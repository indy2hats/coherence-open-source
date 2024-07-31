<?php

namespace Database\Factories;

use App\Models\UserWish;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserWishFactory extends Factory
{
    protected $model = UserWish::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => Carbon::now(),
            'type' => $this->faker->randomElement(config('seeder-config.wish.type')),
            'title' => ucwords($this->faker->unique()->word),
            'file_type' => 'Text',
            'image' => '<p>'.$this->faker->paragraph(1).'</p>',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
