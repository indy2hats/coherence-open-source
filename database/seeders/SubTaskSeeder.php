<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskAssignedUsers;
use Illuminate\Database\Seeder;

class SubTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($userId)
    {
        return Task::factory()->count(1)->create()->each(function ($task, $index) use ($userId) {
            TaskAssignedUsers::factory()->count(1)->create(['task_id' => $task->id, 'user_id' => $userId]);
            Task::factory()->count(1)->create(['parent_id' => $task->id])->each(function ($subTask, $index) use ($userId) {
                TaskAssignedUsers::factory()->count(1)->create(['task_id' => $subTask->id, 'user_id' => $userId]);
            });
        });
    }
}
