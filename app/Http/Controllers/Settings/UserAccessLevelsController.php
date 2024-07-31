<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use App\Traits\GeneralTrait;
use  Illuminate\Http\Request;

class UserAccessLevelsController extends Controller
{
    use GeneralTrait;

    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        $roles = $this->roles();
        $permissions = $this->permissions();

        return view('settings.access-levels.index', compact('roles', 'permissions'));
    }

    public function store()
    {
        $res = $this->settingsService->storeUserAccessLevel();

        return response()->json($res);
    }

    public function addRole(Request $request)
    {
        $request->validate([
            'role_name' => 'required | unique:roles,display_name'
        ]);

        $this->createRole(['name' => request('role_name'), 'display_name' => request('role_name')]);
        $res = $this->settingsService->getUserAccessList('Role added successfully');

        return response()->json($res);
    }

    public function addPermission(Request $request)
    {
        $request->validate([
            'permission_name' => 'required | unique:permissions,display_name'
        ]);

        $permission = $this->createPermission(['name' => request('permission_name'), 'display_name' => request('permission_name')]);
        $role = $this->getAdministratorRole();
        $role->givePermissionTo($permission->name);

        $res = $this->settingsService->getUserAccessList('Permission added successfully');

        return response()->json($res);
    }

    public function delete()
    {
        $role = $this->findRoleById(request('role_id'));

        if (count($role->users)) {
            return response()->json(['error' => 'Unable to delete. Users assigned for this role']);
        }

        $role->delete();

        $res = $this->settingsService->getUserAccessList('Role deleted successfully');

        return response()->json($res);
    }
}
