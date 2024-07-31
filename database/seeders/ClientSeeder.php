<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($client = null)
    {
        for ($i = 0; $i < 2; $i++) {
            $client[$i] = self::createClient();
        }

        if (is_null($client)) {
            self::createNewClient();
            self::createNewClient();
        } else {
            foreach ($client as $id) {
                Client::factory()->create(['user_id' => $id]);
            }
        }
    }

    public static function createNewClient()
    {
        $clientUser = self::createClient();
        Client::factory()->count(1)->create(['user_id' => $clientUser]);
    }

    public static function createClient()
    {
        $user = User::factory()->create([
            'role_id' => 4,
            'phone' => null,
            'employee_id' => null,
            'department_id' => null,
            'designation_id' => null,
        ]);

        $roleName = Role::find($user->role_id)->name;
        $user->assignRole($roleName);

        return $user->id;
    }
}
