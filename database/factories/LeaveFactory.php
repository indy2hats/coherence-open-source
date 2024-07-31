<?php

namespace Database\Factories;

use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveFactory extends Factory
{
    protected $model = Leave::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $leaveDate = Carbon::now()->addDays(rand(5, 180))->format('Y-m-d');

        return [
            'user_id' => User::active()->whereIn('role_id', [3, 2, 6])->inRandomOrder()->first()->id,
            'from_date' => $leaveDate,
            'to_date' => $leaveDate,
            'type' => $this->faker->randomElement(config('seeder-config.leaves.leave-type')),
            'session' => $this->faker->randomElement(config('seeder-config.leaves.leave-session')),
            'lop' => 'No',
            'reason' => $this->faker->paragraph(1),
            'status' => 'Waiting',
            'approved_by' => null
        ];
    }
}
