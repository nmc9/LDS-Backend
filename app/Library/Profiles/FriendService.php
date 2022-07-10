<?php

namespace App\Library\Profiles;

use App\Http\Requests\Profile\RegisterRequest;
use App\Models\Friend;
use App\Models\User;

class FriendService
{

    public function addFriend($from_id, $to_id, $token = null)
    {
        $token = $token ?? $this->generateToken();
        Friend::create([
            'from_user_id' => $from_id,
            'to_user_id' => $to_id,
            'token' => $token
        ]);
        return $token;
    }



    public function generateToken(){
        return \Str::random(60);
    }
}
