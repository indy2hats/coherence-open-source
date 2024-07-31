<?php

namespace Database\Factories;

use App\Models\QaIssue;
use App\Models\Task;
use App\Models\TaskAssignedUsers;
use App\Models\TaskRejection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskRejectionFactory extends Factory
{
    protected $model = TaskRejection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $levelOfSeverityList = config('seeder-config.qa-feedback.level-of-severity');
        $score = $this->faker->randomElement(array_keys($levelOfSeverityList));
        $levelOfSeverity = $levelOfSeverityList[$score];

        $task = Task::where(['status' => 'In Progress'])->with('users')->doesnthave('users_rejections')->pluck('id')->toArray();
        $taskId = $this->faker->unique()->randomElement($task);
        $taskUser = TaskAssignedUsers::where('task_id', $taskId)->inRandomOrder()->first();

        return [
            'user_id' => $taskUser->user_id,
            'task_id' => $taskId,
            'severity' => $levelOfSeverity,
            'reason' => QaIssue::inRandomOrder()->first()->id,
            'comments' => $this->faker->unique()->paragraph(1),
            'score' => $score,
            'rejected_by' => User::active()->whereIn('role_id', [1, 3, 2, 6])->inRandomOrder()->first()->id,
            'created_at' => $taskUser->updated_at,
            'updated_at' => $taskUser->updated_at,
        ];
    }
}
