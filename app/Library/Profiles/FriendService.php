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
            'token' => $token,
            'accepted' => false,
        ]);
        return $token;
    }

    public function getFriends($user){
        $recievedBy = $this->getFriendsReceivedBy($user);
        $sentTo = $this->getFriendsSentTo($user);

        return $recievedBy->merge($sentTo);
    }

    public function getFriendsWithUnaccepted($user){
        $recievedBy = $this->getFriendsReceivedBy($user,false);
        $sentTo = $this->getFriendsSentTo($user,false);

        return $recievedBy->merge($sentTo);
    }

    public function getFriendsReceivedBy($user, $accepted = true){
        return $accepted ? $user->receievedFriends : $user->allReceievedFriends;
    }

    public function getFriendsSentTo($user, $accepted = true){
        return $accepted ? $user->sentFriends : $user->allSentFriends;
    }



    public function generateToken(){
        return \Str::random(60);
    }
}
