<?php

namespace App\Library\Invitations;

use App\Http\Requests\Profile\RegisterRequest;
use App\Library\Constants;
use App\Library\Invitations\InvitationService;
use App\Mail\InvitationReminderMail;
use App\Mail\InvitationRequestMail;
use App\Models\Friend;
use App\Models\User;

class InvitationMailService
{

    public function sendBasedOnCreatedInviations($results,$event,$auth){
        foreach ($results as $result) {
            if($result[0] == InvitationService::INVITATION_CREATED){
                $this->sendRequestMail($auth,$result[1],$event,$result[2]);
            }else if ($result[0] == InvitationService::INVITATION_ALREADY_SENT){
                $this->sendReminderMail($auth,$result[1],$event,$result[2]);
            }
        }
    }


    public function sendRequestMail($invitingUser,$invitedUser,$event,$token){

        \Mail::to($invitedUser)->send(new InvitationRequestMail(
            $invitingUser->name,
            $invitedUser->name,
            $this->toMailableEvent($event),
            $this->getAcceptUrl($token),
            $this->getDeclineUrl($token),
        ));  
    }

    public function sendReminderMail($invitingUser,$invitedUser,$event,$token){

        \Mail::to($invitedUser)->send(new InvitationReminderMail(
            $invitingUser->name,
            $invitedUser->name,
            $this->toMailableEvent($event),
            $this->getAcceptUrl($token),
            $this->getDeclineUrl($token),
        ));  
    }


    public function getAcceptUrl($token){
        return route('invitation.response') . "?token=" . $token . "&response=Accept";

    }

    public function getDeclineUrl($token){
        return  route('invitation.response') . "?token=" . $token . "&response=Decline";
    }

    public function toMailableEvent($event){
        return (object) [
            "name" => $event->name,
            "location" => $event->location,
            "start_readable" => $event->start_datetime->format(Constants::DATETIME_READABLE_SHORT),
            "end_readable" => $event->end_datetime->format(Constants::DATETIME_READABLE_SHORT),
        ];    
    }
}
