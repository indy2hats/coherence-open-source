<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\UserBankDetails;
use App\Models\WorkNote;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersWithRoles = [
            'admin' => [
                [
                    'first_name' => 'admin',
                    'last_name' => null,
                    'email' => 'admin@epmsdemo.com',
                    'password' => '3pw2d3w0!',
                    'role_id' => 1,
                    'department_id' => Department::factory()->create(['name' => 'Administrator'])->id
                ]

            ],
            'employee' => [
                [
                    'email' => 'demo.employee@epmsdemo.com',
                    'role_id' => 3,
                ]
            ],

            'project-manager' => [
                [
                    'email' => 'demo.pr@epmsdemo.com',
                    'role_id' => 2,
                    'department_id' => Department::factory()->create(['name' => 'Project Co-ordinator'])->id
                ],
            ],
            'hr-manager' => [
                [
                    'role_id' => 5,
                    'department_id' => Department::factory()->create(['name' => 'Human Resource'])->id
                ]
            ],
            'team-lead' => [
                [
                    'email' => 'demo.tl@epmsdemo.com',
                    'role_id' => 6,
                    'department_id' => Department::where(['name' => 'Project Co-ordinator'])->first()->id
                ]
            ],

        ];
        $client = [];
        $i = 0;
        foreach ($usersWithRoles as $roleUser) {
            foreach ($roleUser as $user) {
                $user = self::createUserWithRole($user);
                if ($user->role_id == 4) {
                    $client[$i++] = $user->id;
                } else {
                    $this->actionsForNonClients($user);
                }
            }
        }
        $this->call(ClientSeeder::class, false, compact('client'));
    }

    public static function createUserWithRole($user)
    {
        $user = User::factory()->create($user);
        $roleName = Role::find($user->role_id)->name;
        $user->assignRole($roleName);

        return $user;
    }

    public function actionsForNonClients($user)
    {
        UserBankDetails::factory()->create(['user_id' => $user->id]);
        if (in_array($user->role_id, [1, 2, 3, 5, 6])) {
            $userId = $user->id;
            $this->createUserTask($userId);
            $this->addEasyAccessLinks($user);
            WorkNote::factory()->count(1)->create(['user_id' => $userId]);
        }
        if (in_array($user->role_id, [2, 3, 5])) {
            CheckListSeeder::addChecklist($userId);
            CheckListSeeder::checklistReport($userId);
        }
    }

    public static function addEmployeeDependency($user)
    {
        UserBankDetails::factory()->count(1)->create(['user_id' => $user->id]);
        $roleName = Role::find($user->role_id)->name;
        $user->assignRole($roleName);

        return $user;
    }

    public function addEasyAccessLinks($user)
    {
        $list = unserialize($user->easy_access);
        array_push($list, ['name' => 'Google', 'link' => 'https://www.google.com/']);
        array_push($list, ['name' => 'Youtube', 'link' => 'https://www.youtube.com/']);
        $list = serialize($list);
        User::find($user->id)->update(['easy_access' => $list]);
    }

    public function createUserTask($userId)
    {
        $this->call(TaskInProgressSeeder::class, false, compact('userId'));
        $this->call(SubTaskSeeder::class, false, compact('userId'));
        $this->call(TaskNotStartedSeeder::class, false, compact('userId'));
        $this->call(TaskDoneSeeder::class, false, compact('userId'));
        $this->call(TaskArchivedSeeder::class, false, compact('userId'));
        $this->call(DailyStatusReportSeeder::class, false, compact('userId'));
    }
}
