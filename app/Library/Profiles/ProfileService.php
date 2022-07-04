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

    public function searchProfiles($term)
    {
        return User::where('email','LIKE',"%$term%")->orWhere('name','LIKE',"%$term%")->get();
    }

    public function randomProfiles()
    {
        return User::inRandomOrder()->get();
    }
}
