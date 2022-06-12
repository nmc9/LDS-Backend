<?php

namespace App\Library\Profiles;

use App\Http\Requests\Profile\RegisterRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthProfileService
{

    public function checkUser($email,$password)
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! \Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user;
    }
}
