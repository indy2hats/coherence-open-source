<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Login Controller
        |--------------------------------------------------------------------------
        |
        | This controller handles authenticating users for the application and
        | redirecting them to your home screen. The controller uses a trait
        | to conveniently provide its functionality to your applications.
        |
       */

    use AuthenticatesUsers;

    use GeneralTrait;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->middleware('guest')->except('logout');
        $this->authService = $authService;
    }

    /**
     * This method is responsible for rendering the login form view using Inertia.
     *
     * @return \Inertia\Response
     */
    public function loginForm(): Response
    {
        // Render the 'Login' view using Inertia.
        return Inertia::render('Login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        return $this->authService->login($request);
    }

    public function verifyEmail()
    {
        $token = request('email_token');
        if (! $token) {
            return  view('auth.login-verify')
                ->with('error', 'Email Verification Token not provided.');
        }

        $user = $this->getUserByEmailToken($token);

        if (! $user) {
            return view('auth.login-verify')
                ->with('error', 'Invalid Email Verification Token.');
        }

        if (Carbon::now() >= Carbon::parse($user->email_token_expired_at)) {
            return redirect(route('login'))->withErrors([
                'auth_token' => 'Email verification token expired. please login again'
            ]);
        }

        return $this->authService->generateSession($user);
    }
}
