<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskAssignedUsers;
use App\Models\TaskSession;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskDoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($userId)
    {
        $taskDone = config('seeder-config.tasks.task-status.done');
        $taskDate = self::getRandomWeekdays();

        return  Task::factory()->count(1)->create([
            'status' => $taskDone,
            'created_at' => $taskDate,
            'updated_at' => $taskDate
        ])->each(function ($task, $index) use ($userId) {
            self::createTaskSession($userId, $task);
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
}
