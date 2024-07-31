<?php

namespace Database\Factories;

use App\Models\Compensatory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompensatoryFactory extends Factory
{
    protected $model = Compensatory::class;

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
            'date' => $leaveDate,
            'session' => $this->faker->randomElement(config('seeder-config.leaves.leave-session')),
            'reason' => $this->faker->paragraph(1),
            'status' => 'Pending',
            'approved_by' => null,
            'reason_for_rejection' => null,
            'created_at' => $leaveDate,
            'updated_at' => $leaveDate
        ];
    }
}
