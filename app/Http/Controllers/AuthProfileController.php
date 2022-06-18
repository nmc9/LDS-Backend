<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ForgotPasswordRequest;
use App\Http\Requests\Profile\LoginRequest;
use App\Http\Requests\Profile\LogoutRequest;
use App\Http\Requests\Profile\RegisterRequest;
use App\Http\Requests\Profile\ResetPasswordRequest;
use App\Library\Profiles\AuthProfileService;
use App\Library\Profiles\AvailabilityService;
use App\Library\Profiles\ProfileService;
use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Http\Request;

class AuthProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('logout');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request, ProfileService $service, AvailabilityService $availabilityService)
    {
        $user = $service->storeProfile([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
        ]);

        if($request->availabilities){
            $availabilityService->store($user,$request->availabilities);
        }

        \Mail::to($user)->send(new RegisterMail($user->name));

        return response()->json([
            'user' => $user,
            'token' => $service->createToken($user,$request->device_name)
        ],201);
    }

    public function login(LoginRequest $request, AuthProfileService $service, ProfileService $profile_service)
    {

        $user = $service->checkUser($request->email,$request->password);

        return response()->json([
            'user' => $user,
            'token' => $profile_service->createToken($user,$request->device_name)
        ]);
    }

    public function logout(LogoutRequest $request)
    {
        // Revoke all tokens...
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 1,
            'msg' => "Logged Out"
        ]);
    }

    // public function resetPassword(ResetPasswordRequest $request)
    // {

    //     $status = Password::reset(
    //         $request->only('email', 'password', 'password_confirmation', 'token'),
    //         function ($user, $password) {
    //             $user->forceFill([
    //                 'password' => Hash::make($password)
    //             ])->setRememberToken(Str::random(60));

    //             $user->save();

    //             event(new PasswordReset($user));
    //         }
    //     );

    //     return $status === Password::PASSWORD_RESET
    //     ? redirect()->route('login')->with('status', __($status))
    //     : back()->withErrors(['email' => [__($status)]]);
    // }

    // public function forgotPassword(ForgotPasswordRequest $request)
    // {
    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );
    //     return reponse()->json([
    //         'status' => $status === Password::RESET_LINK_SENT,
    //         'msg' => __($status)
    //     ]);
    // }
}
