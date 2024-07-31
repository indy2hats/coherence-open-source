<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAssignedUsers;
use App\Models\TaskDocument;
use App\Models\TaskSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskInProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($userId)
    {
        $taskInProgress = config('seeder-config.tasks.task-status.progress');
        $taskDate = self::getRandomWeekdays();

        return Task::factory()->count(1)->create([
            'status' => $taskInProgress,
            'start_date' => $taskDate,
            'created_at' => $taskDate,
            'updated_at' => $taskDate
        ])->each(function ($task, $index) use ($userId) {
            $taskId = $task->id;
            self::createTaskSession($userId, $task);
            TaskDocument::factory()->count(1)->create(['task_id' => $taskId]);
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

    public static function createTaskSession($userId, $task)
    {
        $taskDate = Carbon::parse($task->created_at);
        TaskAssignedUsers::factory()->count(1)->create([
            'task_id' => $task->id,
            'user_id' => $userId,
            'created_at' => $taskDate,
            'updated_at' => $taskDate
        ])->each(function ($taskAssigned, $index) use ($userId, $task, $taskDate) {
            $taskId = $task->id;

            $sessionStartDate = Carbon::parse($taskDate->format('Y-m-d H:i:s'));
            $sessionEndDate = Carbon::parse($taskDate->addHours(rand(1, 10))->format('Y-m-d H:i:s'));
            $total = $sessionEndDate->diffInMinutes($sessionStartDate, true);

            $taskEstimatedTime = ($task->estimated_time ?? $total) * 60;
            $billed = ($total > $taskEstimatedTime) ? $taskEstimatedTime : $total;

            TaskSession::factory()->count(1)->create([
                'task_id' => $taskId,
                'user_id' => $userId,
                'start_time' => $sessionStartDate,
                'end_time' => $sessionEndDate,
                'billed_today' => $billed,
                'total' => $total,
                'created_at' => $sessionStartDate,
                'updated_at' => $sessionStartDate,
            ]);
            Task::find($taskId)->update(['time_spent' => $task->time_spent + ($total / 60)]);
        });
    }

    public static function taskOverDue()
    {
        $taskInProgress = config('seeder-config.tasks.task-status.progress');
        Project::factory()->count(1)->create(['category' => 'External'])->each(function ($project, $index) use ($taskInProgress) {
            $taskDate = self::getRandomWeekdays();
            Task::factory()->count(1)->create([
                'status' => $taskInProgress,
                'project_id' => $project->id,
                'start_date' => Carbon::now()->subDays(rand(10, 14))->format('Y-m-d'),
                'end_date' => Carbon::now()->subDays(rand(5, 10))->format('Y-m-d'),
                'created_at' => Carbon::now()->subDays(rand(14, 20)),
                'updated_at' => Carbon::now()->subDays(rand(14, 20))
            ])->each(function ($task, $index) {
                $taskId = $task->id;
                $userId = User::inRandomOrder()->first()->id;
                self::createTaskSessionOverDue($userId, $task);
            });
        });
    }

    public static function createTaskSessionOverDue($userId, $task)
    {
        TaskAssignedUsers::factory()->count(1)->create([
            'task_id' => $task->id,
            'user_id' => $userId,
            'created_at' => $task->created_at,
            'updated_at' => $task->created_at
        ])->each(function ($taskAssigned, $index) use ($task) {
            $taskDate = Carbon::parse($task->created_at);
            $taskId = $taskAssigned->task_id;

            $sessionStartDate = Carbon::parse($taskDate->format('Y-m-d H:i:s'));
            $sessionEndDate = Carbon::parse($taskDate->addHours(rand(1, 10))->format('Y-m-d H:i:s'));
            $total = $sessionEndDate->diffInMinutes($sessionStartDate, true) ?? 0;

            $taskEstimatedTime = ($task->estimated_time ?? $total) * 60;
            $billed = ($total > $taskEstimatedTime) ? $taskEstimatedTime : $total;

            TaskSession::factory()->count(1)->create([
                'task_id' => $taskId,
                'user_id' => $taskAssigned->user_id,
                'start_time' => $sessionStartDate,
                'end_time' => $sessionEndDate,
                'billed_today' => $billed,
                'total' => $total,
                'created_at' => $sessionStartDate,
                'updated_at' => $sessionStartDate,
            ]);
            Task::find($taskId)->update(['time_spent' => $task->time_spent + ($total / 60)]);
        });
    }
}
