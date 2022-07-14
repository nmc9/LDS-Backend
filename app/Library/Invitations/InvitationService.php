<?php

namespace App\Library\Invitations;

use App\Http\Requests\Profile\RegisterRequest;
use App\Library\Constants;
use App\Library\OverlappingTime;
use App\Library\Profiles\FriendService;
use App\Mail\InvitationReminderMail;
use App\Mail\InvitationRequestMail;
use App\Models\Friend;
use App\Models\Invitation;
use App\Models\User;

class InvitationService
{
    private $overlappingTime;

    const NOT_FRIENDS = 0;
    const INVITATION_CREATED = 1;
    const INVITATION_ALREADY_SENT = 2;
    const INVITATION_ALREADY_DECLINED = 3;


    public function __construct(OverlappingTime $overlappingTime)
    {
        $this->overlappingTime = $overlappingTime;
    }

    public function createInvitations(FriendService $friendService, $users, $event_id, $auth, )
    {
        $users = User::whereIn('id',$users)->get();
        return collect($users)->map(function($user) use ($friendService,$auth,$event_id){
            if($friendService->arentFriends($auth,$user->id)){
                return [static::NOT_FRIENDS,$user, "Not Friends"];
            }

            $invitation = $this->getInvitiation($user->id, $event_id);
            if($invitation){

                if($invitation->status == Constants::INVITATION_DECLINED){
                    return [static::INVITATION_ALREADY_DECLINED,$user, "Invitaiton Already Declined"];
                }
                return [static::INVITATION_ALREADY_SENT,$user, $invitation->token];
            }

            $invitation = $this->addInvitation($user->id, $event_id, $auth->id);
            return [static::INVITATION_CREATED,$user, $invitation->token];
            
        });
    }

    public function addInvitation($user_id, $event_id, $auth_id){
        return Invitation::create([
            "user_id" => $user_id,
            "event_id" => $event_id,
            "inviter_id" => $auth_id,
            "status" => Constants::INVITATION_PENDING,
            "token" => $this->generateToken(),
        ]);
    }

    public function generateToken(){
        return \Str::random(60);
    }

    public function getInvitiation($user, $event){
        return Invitation::where("user_id",$user)->where("event_id",$event)->first();
    }


}
