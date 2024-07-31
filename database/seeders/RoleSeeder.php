<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($roles = [])
    {
        app()['cache']->forget('spatie.permission.cache');
        app()['cache']->forget('spatie.role.cache');
        Schema::disableForeignKeyConstraints();
        Role::truncate();
        Schema::enableForeignKeyConstraints();

        foreach ($roles as $role) {
            $role = Role::create([
                'name' => $role,
            ]);
        }
    }
}
