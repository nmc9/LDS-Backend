<?php

namespace App\Library\Bringables;

use App\Http\Requests\Profile\RegisterRequest;
use App\Library\Constants;
use App\Library\Invitations\InvitationService;
use App\Mail\BringableMail;
use App\Mail\InvitationImaginaryRequestMail;
use App\Mail\InvitationReminderMail;
use App\Mail\InvitationRequestMail;
use App\Models\Friend;
use App\Models\User;

class BringableMailService
{

    public function send($assigned_id,$bringable_name,$event_name){
        $user = User::find($assigned_id);
        \Mail::to($user)->send(new BringableMail(
            $user->name,
            $event_name,
            $bringable_name
        ));  
    }

}
