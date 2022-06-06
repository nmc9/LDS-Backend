<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ForgotPasswordRequest;
use App\Http\Requests\Profile\LoginRequest;
use App\Http\Requests\Profile\ResetPasswordRequest;
use App\Library\Profiles\AuthProfileService;
use App\Models\User;
use Illuminate\Http\Request;

class AuthProfileController extends Controller
{


    public function login(LoginRequest $request, AuthProfileService $service, ProfileService $profile_service)
    {

        $user = $service->checkUser($email,$password);

        return response()->json([
            'user' => $user,
            'token' => $profile_service->createToken($request->device_name)
        ]);
    }

    public function logout(LogoutRequest $request)
    {
        // Revoke all tokens...
        $request->user->tokens()->delete();

        return response()->json([
            'status' => 1,
            'msg' => "Logged Out"
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return reponse()->json([
            'status' => $status === Password::RESET_LINK_SENT,
            'msg' => __($status)
        ]);
    }
}
