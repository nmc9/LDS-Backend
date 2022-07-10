<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    
    public function store(Request $request, Event $event){

        $token = \Str::random(60);
        $user_ids = [1];
        foreach ($user_ids as $user_id) {
            // If Auth::user and $user_id == friends else add to failed
            // If (event_id,user_id) already exists, add to failed, send a reminder notificaiotn) 
            Invitation::create([
                "user_id" => $user_id,
                "event_id" => $event_id,
                "inviter_id" => Auth::user()->id,
                "status" => Constants::INVITATION_PENDING,
                "token" => $token,
            ]);

            // $this->sendInvitaitonEmail($token)
        }
    }


    public function update(Request $request, Event $event, Invitation $invitation){

    }


    
}
