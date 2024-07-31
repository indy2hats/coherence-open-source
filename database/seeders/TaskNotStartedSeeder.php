<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskAssignedUsers;
use Illuminate\Database\Seeder;

class TaskNotStartedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($userId)
    {
        $taskNotStarted = config('seeder-config.tasks.task-status.backlog');

        return  Task::factory()->count(1)->create(['status' => $taskNotStarted])->each(function ($task, $index) use ($userId) {
            TaskAssignedUsers::factory()->count(1)->create(['task_id' => $task->id, 'user_id' => $userId]);
        });
    }
}
