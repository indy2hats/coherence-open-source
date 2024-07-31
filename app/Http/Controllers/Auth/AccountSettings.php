<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repository\UserRepository;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class AccountSettings extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $qrImage = $google2faSecret = null;
        if ($user->google2fa_secret == null) {
            $google2fa = app('pragmarx.google2fa');
            $google2faSecret = $google2fa->generateSecretKey();
            $userEmail = $user->email;
            Session::put('google2faSecret', $google2faSecret);

            $qrImage = $google2fa->getQRCodeInline(
                config('app.name'),
                $userEmail,
                $google2faSecret
            );
        }

        return view('profile.settings.index', compact('qrImage', 'google2faSecret'));
    }

    public function enableTwoFactorAuthentication(Request $request)
    {
        $request->validate(
            ['otp' => 'required|numeric'],
            [
                'otp.required' => config('google2fa.error_messages.cannot_be_empty'),
                'otp.numeric' => config('google2fa.error_messages.wrong_otp')
            ]
        );
        try {
            $secret = Session::get('google2faSecret');

            $google2fa = app('pragmarx.google2fa');

            if ($google2fa->verifyKey($secret, $request['otp'])) {
                User::find(Auth::user()->id)->update([
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

    public function disableTwoFactorAuthentication(Request $request)
    {
        try {
            if ($request['confirm']) {
                User::find(Auth::user()->id)->update([
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

    public function resetPassword()
    {
        return view('profile.changepassword');
    }

    public function disableUserTwoFactorAuthentication(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|numeric'
            ]);

            $disable2FA = UserRepository::disableTwoFactorAuthentication((int) $request->id);

            return $disable2FA ?
              response()->json(['status' => true, 'message' => config('google2fa.error_messages.disable_success')]) :
              response()->json(['status' => false, 'message' => config('google2fa.error_messages.disable_failed')]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => config('google2fa.error_messages.disable_failed')]);
        }
    }
}
