<?php

namespace App\Library\Profiles;

use App\Http\Requests\Profile\RegisterRequest;
use App\Mail\FriendRequestMail;
use App\Mail\NotificationFriendRequestMail;
use App\Models\Friend;
use App\Models\User;

class FriendMailService
{

    public function sendRequestNotification($requestingUser,$friendUser){
        \Mail::to($requestingUser)->send(new NotificationFriendRequestMail(
            $friendUser->name,
            $requestingUser->name,
        ));
    }
    public function sendRequestMail($requestingUser,$friendUser,$token){
        \Mail::to($friendUser)->send(new FriendRequestMail(
            $requestingUser->name,
            $friendUser->name,
            $this->getAcceptUrl($token),
            $this->getDeclineUrl($token),
        ));  
    }


    public function getAcceptUrl($token){
        return route('friend.response') . "?token=" . $token . "&response=Accept";

    }

    public function getDeclineUrl($token){
        return route('friend.response') . "?token=" . $token . "&response=Decline";
    }
}
