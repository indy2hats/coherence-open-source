<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Repository\FetchData;
use App\Services\HomeService;
use App\Traits\GeneralTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    use GeneralTrait;

    private $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fetchData = new FetchData();

        if ($this->getCurrentUser()->hasRole('administrator')) {
            return redirect('/dashboard');
        } elseif ($this->getCurrentUser()->hasAnyRole(['project-manager', 'employee', 'hr-manager'])) {
            $fetchData->checkLastDaySession();

            return redirect('/dashboard');
        } elseif ($this->getCurrentUser()->hasRole('client')) {
            return redirect('/client-sheet');
        } else {
            return redirect('/login');
        }
    }

    public function sendAlert()
    {
        $user = User::findOrFail(request('user_id'));
        if ($user->hasRole('employee|project-manager|client')) {
            $user->notify(new \App\Notifications\IdlePush('title', 'body'));
        }

        return response()->json(['success' => true, 'status' => 'subscription added']);
    }

    public function changePasswordAction(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w]).{6,}$/',
        ]);

        if (! Hash::check(request('current_password'), $this->getCurrentUser()->password)) {
            return response()->json(['flag' => false, 'message' => 'Invalid current password.']);
        }
        $this->homeService->updatePassword();

        return response()->json(['flag' => true, 'message' => 'Success.Please use new password from next login.']);
    }

    public function showEmployeeDashboard()
    {
        $counts = $this->homeService->getCountDetails();

        $totalHours = $this->homeService->getTotalHours();

        $inProgressTasks = Task::with(['users' => function ($q) {
            $q->where('user_id', '=', Auth::user()->id);
        }])->whereHas('users', function ($query) {
            $query->where('user_id', '=', Auth::user()->id);
        })->where('status', '=', 'In Progress')->get();

        $total = $this->homeService->getThisWeek();

        return view('dashboard.employeedashboard', compact('counts', 'totalHours', 'inProgressTasks', 'total'));
    }

    public function saveSubscription($id)
    {
        $user = User::findOrFail($id);
        $user->updatePushSubscription(request('endpoint'), request('keys.p256dh'), request('keys.auth'));

        return response()->json(['success' => true, 'status' => 'subscription added']);
    }

    public function showProfile()
    {
        $details = $this->homeService->getDetails();
        $counts = $this->homeService->getCountDetails();
        $totalHours = $this->homeService->getTotalHours();
        $total = $this->homeService->getThisWeek();
        $leave = $this->homeService->getLeaves();
        $rejectionCount = $this->homeService->getRejectionCount();
        $rejections = $this->homeService->getRejections();
        $rejectionIndex = $this->homeService->getRejectionIndex();

        $clientProjectsCount = 0;
        $clientCompanies = [];
        if ($this->isClient()) {
            $clientProjectsCount = $this->homeService->getClientProjectsCount();
            $clientCompanies = $this->homeService->getClientCompanies();
        }

        return view('profile.index', compact('details', 'total', 'totalHours', 'counts', 'leave', 'rejectionCount', 'rejections', 'rejectionIndex', 'clientProjectsCount', 'clientCompanies'));
    }

    public function search(Request $request)
    {
        $data = $this->homeService->search($request);

        return response()->json($data);
    }
}
