<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskAssignedUsers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskAssignedUsersFactory extends Factory
{
    protected $model = TaskAssignedUsers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::active()->whereIn('role_id', [1, 2, 3])->inRandomOrder()->first()->id,
        ];
    }
}
