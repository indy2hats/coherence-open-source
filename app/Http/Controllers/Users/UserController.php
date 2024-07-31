<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use GeneralTrait;

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /** Used to fetch all details needed for users.index */
    public function index()
    {
        $users = $this->userService->getUsers();
        $roles = $this->getRoles();
        $oneUser = $this->userService->getUser();

        return view('users.index', compact('users', 'roles', 'oneUser'));
    }

    /** Used to create new User and will assign roles to them*/
    public function store(StoreUserRequest $request)
    {
        $this->userService->store($request);

        return response()->json(['message' => 'User Added successfully']);
    }

    /** Display the User Details. */
    public function show($id)
    {
        $user = $this->getUserByid($id);

        return view('users.view', compact('user'));
    }

    /** Used to return user details to edit modal*/
    public function edit($id)
    {
        $user = $this->userService->getUserWithDesignationAndDept($id);
        $roles = $this->getRoles();

        return view('users.edit', compact('user', 'roles'));
    }

    /** Used to update user details, delete existing image and add new, change roles accordingly */
    public function update(UpdateUserRequest $request, $id)
    {
        $img = $this->userService->update($request, $id);

        return response()->json(['message' => 'User details updated successfully', 'img' => $img]);
    }

    /** Used to remove users*/
    public function destroy($id)
    {
        $this->userService->destroy($id);

        return response()->json(['message' => 'User deleted successfully']);
    }

    /** Ajax function for edit user view*/
    public function getSingleUserAjax(Request $request)
    {
        $Id = request('employee_Id');
        $oneUser = $this->userService->getSingleUser($Id);

        $content = view('users.view', compact('oneUser'))->render();
        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Retrieves the users grid data via AJAX request and returns it as a JSON response.
     *
     * @param  Request  $request  The HTTP request object containing the AJAX request data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status and data.
     */
    public function getUsersGridAjax(Request $request)
    {
        $roles = $this->getAllRoles();
        $usersArray = $this->userService->getUsersForUsersGridAjax($request);
        $users = $usersArray[0];
        $oneUser = $usersArray[1];

        $content = view('users.grid', compact('roles', 'oneUser', 'users'))->render();
        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /** typeAhead function for listing users */
    public function getTypheadDataUser()
    {
        $typehead = $this->getAllUsers();
        $designations = $this->getDesignationNames();
        $departments = $this->getDepartmentNames();

        return response()->json(['data' => $typehead, 'designations' => $designations, 'departments' => $departments]);
    }

    public function wishNotified()
    {
        $this->userService->wishNotified();
    }

    public function eodReportNotified()
    {
        $this->userService->eodReportNotified();
    }

    private function getNewEmployeeCode()
    {
        $latestEmployeeCode = User::lastEmployeeCode();
        $prefix = config('general.user-code.prefix');
        if (! isset($latestEmployeeCode->employee_id)) {
            $startingSeries = config('general.user-code.start-series');
            $latestUserCode = $prefix.$startingSeries;

            return $latestUserCode;
        }
        $employeeCodeArray = explode($prefix, $latestEmployeeCode->employee_id);

        return $prefix.(string) str_pad($employeeCodeArray[1] + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getCreateUserData()
    {
        $newEmployeeCode = $this->getNewEmployeeCode();

        return response()->json(['newEmployeeCode' => $newEmployeeCode]);
    }
}
