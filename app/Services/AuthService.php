<?php

namespace App\Services;

use App\Notifications\AuthenticateUser;
use App\Repository\UserRepository;
use App\Traits\GeneralTrait;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class AuthService
{
    use GeneralTrait;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function resetPassword($request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function login($request)
    {
        $email = strtolower($request->email);
        $password = $request->password;

        $remember_me = $request->has('remember_me') ? true : false;

        $user = $this->getUserByEmail($email);

        if (! empty($user)) {
            if ($this->checkPassword($password, $user->password)) {
                $leaving_date = $user->leaving_date == '' ? 1 : 0;
                if ($leaving_date == 0) {
                    $leaving_date = date('Y-m-d') > $leaving_date ? 1 : 0;
                }

                if ($user->status == 1 && $leaving_date == 1) {
                    $authenticationEnabled = config('app.two_factor_authentication') ?? true;
                    if (! $authenticationEnabled) {
                        return $this->generateSession($user);
                    }

                    $user->update(['email_token' => bin2hex(openssl_random_pseudo_bytes(4)), 'email_token_expired_at' => Carbon::now()->addMinutes(10)]);
                    $this->sendNotification($user, new AuthenticateUser($user));

                    return to_route('email-verify-form');
                }
            }
        }

        return redirect()
            ->back()
            ->withErrors([
                'password' => 'Invalid Credentials '
            ]);
    }

    public function generateSession($user)
    {
        Auth::login($user);
        $info = $this->getCurrentUser();
        $user = $this->userRepository->getUserWithDesignation($info);
        $user->notify(new \App\Notifications\OnLoginSite('title', 'body'));
        $name = $user->full_name;
        $desg = $user->designation->name ?? '';
        $imgpath = $user->image_path;

        $this->putSession('id', $info->id);
        $this->putSession('name', $name);
        $this->putSession('desg', $desg);
        $this->putSession('imgpath', $imgpath);

        if ($info->must_change_password) {
            //TODO::convert this to vue
            return view('layout.changepassword');
        } else {
            if ($this->isClient()) {
                //TODO::route not found
                return redirect('/client-sheet');
            }

            return to_route('dashboard');
        }
    }

    public function accountSettingsIndex()
    {
        $user = $this->getCurrentUser();
        $qrImage = $google2faSecret = null;
        if ($user->google2fa_secret == null) {
            $google2fa = app('pragmarx.google2fa');
            $google2faSecret = $google2fa->generateSecretKey();
            $userEmail = $user->email;
            $this->putSession('google2faSecret', $google2faSecret);

            $qrImage = $google2fa->getQRCodeInline(
                config('app.name'),
                $userEmail,
                $google2faSecret
            );
        }

        return [$qrImage, $google2faSecret];
    }

    public function enableTwoFactorAuthentication($request)
    {
        try {
            $secret = $this->getSession('google2faSecret');
            $google2fa = app('pragmarx.google2fa');

            if ($google2fa->verifyKey($secret, $request['otp'])) {
                $this->updateUser($this->getCurrentUserId(), [
                    'google2fa_secret' => $secret
                ]);

                $google2fa->login();

                return response()->json(['status' => true, 'message' => config('google2fa.error_messages.enable_success')]);
            }

            return response()->json(['status' => false, 'message' => config('google2fa.error_messages.enable_failed')]);
        } catch (Exception $e) {
            Log::info($e->getMessage());

            return response()->json(['status' => false, 'message' => config('google2fa.error_messages.enable_failed')]);
        }
    }

    public function disableTwoFactorAuthentication($request)
    {
        try {
            if ($request['confirm']) {
                $this->updateUser($this->getCurrentUserId(), [
                    'google2fa_secret' => null
                ]);

                $authenticator = app(Authenticator::class)->boot($request);

                if ($authenticator->isAuthenticated()) {
                    $google2fa = app('pragmarx.google2fa');
                    $google2fa->logout();
                }

                return response()->json(['status' => true, 'message' => config('google2fa.error_messages.disable_success')]);
            }

            return response()->json(['status' => false, 'message' => config('google2fa.error_messages.disable_failed')]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => config('google2fa.error_messages.disable_failed')]);
        }
    }

    public function disableUserTwoFactorAuthentication($request)
    {
        try {
            $request->validate([
                'id' => 'required|numeric'
            ]);

            $disable2FA = $this->userRepository->disableTwoFactorAuthentication((int) $request->id);

            return $disable2FA ?
              response()->json(['status' => true, 'message' => config('google2fa.error_messages.disable_success')]) :
              response()->json(['status' => false, 'message' => config('google2fa.error_messages.disable_failed')]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => config('google2fa.error_messages.disable_failed')]);
        }
    }
}
