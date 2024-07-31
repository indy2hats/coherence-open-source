<?php

namespace App\Http\Controllers;

use App\Events\UserCredentialShare;
use App\Models\CredentialAssignedUsers;
use App\Models\Project;
use App\Models\ProjectCredentials;
use App\Models\User;
use App\Models\UserCredential;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class NewCredentialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::orderBy('project_name', 'ASC')->get();
        $credentials = $this->getCredentials();
        $users = User::active()->notClients()->select('id', 'first_name', 'last_name')->orderBy('first_name', 'ASC')->get();

        return view('new-credentials.index', compact('credentials', 'projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'project' => 'required',
            'username' => 'required',
            'password' => 'required',
            'content' => 'required',
        ]);

        $cryptedPassword = $this->customCrypt(request('password'));

        $data = [
            'project_id' => request('project'),
            'type' => request('type'),
            'value' => request('content'),
            'username' => request('username'),
            'password' => $cryptedPassword,
        ];

        ProjectCredentials::create($data);
        $res = [
            'status' => 'ok',
            'message' => 'Credentials Added Successfully',
        ];

        return response()->json($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $isAdmin = Auth::user()->can('manage-project-credentials');
        $userCredentials = $this->ajaxListProjectCredentials($id);
        $projects = Project::orderBy('project_name', 'ASC')->get();
        $users = User::active()->notClients()->select('id', 'first_name', 'last_name')->orderBy('first_name', 'ASC')->get();
        $content = view('new-credentials.list', compact('projects', 'userCredentials', 'users'))->render();
        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $projects = Project::orderBy('project_name', 'ASC')->get();
        $item = ProjectCredentials::where('id', $id)->first();
        isset($item->password) ? $password = Crypt::decryptString($item->password) : $password = null;

        return view('new-credentials.edit', compact('item', 'projects', 'password'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
            'project' => 'required',
            'username' => 'required',
            'password' => 'required',
            'content' => 'required',
        ]);

        $cryptedPassword = $this->customCrypt(request('password'));

        $data = [
            'project_id' => request('project'),
            'type' => request('type'),
            'value' => request('content'),
            'username' => request('username'),
            'password' => $cryptedPassword,
        ];

        ProjectCredentials::find($id)->update($data);

        $res = [
            'status' => 'Saved',
            'message' => 'Credentials Updated Successfully',
        ];

        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProjectCredentials::find($id)->delete();

        return response()->json(['message' => 'Credential deleted successfully']);
    }

    private function getCredentials()
    {
        return UserCredential::whereUserId(Auth::user()->id)->get();
    }

    public function ajaxListProjectCredentials($id)
    {
        $isAdmin = Auth::user()->can('manage-project-credentials');
        if (! $isAdmin) {
            $authId = Auth::user()->id;

            return ProjectCredentials::whereHas('users', function ($q) use ($authId) {
                $q->where('users.id', '=', $authId);
            })->where('project_id', $id)->get();
        } else {
            return ProjectCredentials::with('users')->where('project_id', $id)->get();
        }
    }

    public function shareCredentials($id)
    {
        $projects = Project::orderBy('project_name', 'ASC')->get();
        $users = User::active()->notClients()->select('id', 'first_name', 'last_name')->orderBy('first_name', 'ASC')->get();
        $assignedUsers = CredentialAssignedUsers::where('credential_id', $id)->get();
        $credentialUsers = [];
        foreach ($assignedUsers as $credentialUser) {
            $credentialUsers[] = $credentialUser->user_id;
        }
        $item = ProjectCredentials::where('id', $id)->first();
        isset($item->password) ? $password = Crypt::decryptString($item->password) : $password = null;

        return view('new-credentials.share', compact('item', 'projects', 'password', 'users', 'credentialUsers'));
    }

    public function mailshareCredentials(Request $request)
    {
        $assignedUsers = [];
        $newCredential = ProjectCredentials::find($request->id);
        $oldUsers = CredentialAssignedUsers::where('credential_id', $request->id)->pluck('user_id')->toArray();
        if (! empty(request('credential_assigned_users'))) {
            $assignedUsers = array_unique(array_merge($assignedUsers, request('credential_assigned_users')));
        }
        $users = array_diff($assignedUsers, $oldUsers);
        $users = User::whereIn('id', $users)->get();
        if ($users->count() > 0) {
            event(new UserCredentialShare($newCredential, $users));
        }

        $res = [
            'status' => 'OK',
            'message' => 'Credential Shared Successfully'
        ];

        return response()->json($res);
    }

    public function saveshareCredentials($projectId, $userId)
    {
        $assignedUsers = [$userId];
        $newCredential = ProjectCredentials::find($projectId);
        $oldUsers = CredentialAssignedUsers::where('credential_id', $projectId)->pluck('user_id')->toArray();
        $assignedUsers = array_unique(array_merge($assignedUsers, $oldUsers));
        $newCredential->users()->sync($assignedUsers);

        return redirect('/credentials');
    }

    public function showUserCredential($id)
    {
        $userCredentials = ProjectCredentials::with('project')->whereHas('users', function ($q) use ($id) {
            $q->where('users.id', '=', $id);
        })->get();
        $projects = Project::orderBy('project_name', 'ASC')->get();
        $users = User::active()->notClients()->select('id', 'first_name', 'last_name')->orderBy('first_name', 'ASC')->get();
        $content = view('new-credentials.userlist', compact('projects', 'userCredentials', 'users'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function encryptPassword($data)
    {
        foreach ($data as $value) {
            if ($value->password) {
                $data->$value->password = $this->customDecrypt($value->password ?? '');
            }
        }

        return $data;
    }

    public function customCrypt($word)
    {
        return Crypt::encryptString($word);
    }

    public function customDecrypt($word)
    {
        return Crypt::decryptString($word);
    }
}
