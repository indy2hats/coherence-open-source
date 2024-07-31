<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAssignedUsers;
use App\Models\TaskSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskWithTimerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }

    public static function upskillingTaskStartedWithTimer()
    {
        $taskInProgress = config('seeder-config.tasks.task-status.progress');
        Project::factory()->count(1)->create(['category' => 'Upskilling'])->each(function ($project, $index) use ($taskInProgress) {
            $taskDate = self::getRandomWeekdays();
            Task::factory()->count(1)->create([
                'status' => $taskInProgress,
                'project_id' => $project->id,
                'start_date' => $taskDate,
                'created_at' => $taskDate,
                'updated_at' => $taskDate
            ])->each(function ($task, $index) {
                $taskId = $task->id;
                self::createTaskSession($task);
            });
        });
    }

    public static function productiveTaskStartedWithTimer()
    {
        $taskInProgress = config('seeder-config.tasks.task-status.progress');
        Project::factory()->count(1)->create(['category' => 'External'])->each(function ($project, $index) use ($taskInProgress) {
            $taskDate = self::getRandomWeekdays();
            Task::factory()->count(1)->create([
                'status' => $taskInProgress,
                'project_id' => $project->id,
                'start_date' => $taskDate,
                'created_at' => $taskDate,
                'updated_at' => $taskDate
            ])->each(function ($task, $index) {
                $taskId = $task->id;
                self::createTaskSession($task);
            });
        });
    }

    public static function getRandomWeekdays()
    {
        $taskDate = Carbon::now()->subDays(rand(1, 7));
        if ($taskDate->dayOfWeek === 0 || $taskDate->dayOfWeek === 6) {
            $taskDate = self::getRandomWeekdays();
        }

        return $taskDate;
    }

    public static function createTaskSession($task)
    {
        $taskDate = Carbon::parse($task->created_at);
        TaskAssignedUsers::factory()->count(1)->create([
            'task_id' => $task->id,
            'user_id' => User::whereIn('role_id', [2, 3, 5, 6])->inRandomOrder()->first()->id,
            'created_at' => $taskDate,
            'updated_at' => $taskDate
        ])->each(function ($taskAssigned, $index) {
            $sessionStartDate = Carbon::now()->subMinutes(rand(60, 300))->format('Y-m-d H:i:s');
            TaskSession::factory()->count(1)->create([
                'task_id' => $taskAssigned->task_id,
                'user_id' => $taskAssigned->user_id,
                'start_time' => $sessionStartDate,
                'end_time' => null,
                'billed_today' => null,
                'total' => 0,
                'current_status' => 'Started',
                'created_at' => $sessionStartDate,
                'updated_at' => $sessionStartDate,
            ]);
        });
    }
}
