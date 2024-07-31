<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');
        app()['cache']->forget('spatie.role.cache');
        Schema::disableForeignKeyConstraints();
        Role::truncate();
        Permission::truncate();
        DB::table('role_has_permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        $this->call(PermissionSeeder::class);

        $roles = config('seeder-config.roles');

        $userPermissions = config('seeder-config.user.permissions');

        foreach ($roles as $role) {
            $roleName = str_slug($role);
            $role = Role::create([
                'display_name' => $role,
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
            if (isset($userPermissions[$roleName])) {
                $role->syncPermissions($userPermissions[$roleName]);
            }
        }
    }
}
