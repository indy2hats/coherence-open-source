<?php

namespace Database\Seeders;

use App\Models\TaskAssignedUsers;
use App\Models\TaskStatusType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TaskStatusTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        TaskAssignedUsers::truncate();
        TaskStatusType::truncate();
        Schema::enableForeignKeyConstraints();

        $taskTypes = config('seeder-config.tasks.task-status');

        $i = 0;
        foreach ($taskTypes as  $type) {
            TaskStatusType::create(['title' => $type, 'order' => $i++]);
        }
    }
}
