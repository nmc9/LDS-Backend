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
        return Friend::create([
            'from_user_id' => $from_id,
            'to_user_id' => $to_id,
            'token' => $token,
            'accepted' => false,
        ]);
    }

    public function arentFriends($user,$user2){
        return !$this->areFriends($user,$user2);
    }

    public function areFriends($user,$user2){
        if(! $user instanceof User){
            $user = User::findOrFail($user);
        }
        if(! $user2 instanceof User){
            $user2 = User::findOrFail($user2);
        }
        if($user->sentFriends->contains($user2)){
            return true;
        }else if ($user->receievedFriends->contains($user2)){
            return true;
        }
        return false;
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


    public function searchFriends($user, $term)
    {
        return $this->getFriends($user)->filter(function($item) use ($term){
            return \Str::contains($item->email,$term) || \Str::contains($item->name,$term);
        });
    }


    public function generateToken(){
        return \Str::random(60);
    }

    public function removeFriend($user,$user2){
        Friend::where('to_user_id',$user->id)->where('from_user_id',$user2->id)->delete();
        Friend::where('from_user_id',$user->id)->where('to_user_id',$user2->id)->delete();
    }
}
