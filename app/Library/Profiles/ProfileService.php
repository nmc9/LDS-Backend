<?php

namespace App\Library\Profiles;

use App\Http\Requests\Profile\RegisterRequest;
use App\Models\User;

class ProfileService
{

    public function storeProfile($data)
    {
        return User::create($data);
    }

    public function createToken(User $user, $device_name)
    {
        return $user->createToken($device_name)->plainTextToken;
    }
}
