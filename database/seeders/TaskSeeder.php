<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 3; $i++) {
            User::factory()->count(1)->create(['role_id' => 3])->each(function ($user, $key) {
                $userId = $user->id;
                $user = UserSeeder::addEmployeeDependency($user);

                $this->call(TaskInProgressSeeder::class, false, compact('userId'));
                $this->call(SubTaskSeeder::class, false, compact('userId'));
                $this->call(TaskNotStartedSeeder::class, false, compact('userId'));
                $this->call(TaskDoneSeeder::class, false, compact('userId'));
                $this->call(TaskArchivedSeeder::class, false, compact('userId'));
                $this->call(DailyStatusReportSeeder::class, false, compact('userId'));
                CheckListSeeder::addChecklist($userId);
                CheckListSeeder::checklistReport($userId);
            });
        }
    }
}
