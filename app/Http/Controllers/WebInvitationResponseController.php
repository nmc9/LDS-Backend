<?php

namespace App\Http\Controllers;

use App\Library\Constants;
use App\Mail\NotificationInvitationAccepted;
use App\Mail\NotificationInvitationDeclined;
use App\Models\Friend;
use App\Models\ImaginaryFriend;
use App\Models\Invitation;
use Illuminate\Http\Request;

class WebInvitationResponseController extends Controller
{

    public function response(Request $request)
    {
        if(!$request->token){
            return redirect()->route('invitation.response.callback',["msg" => "No Invitation Found"]);
        }

        $invitation = Invitation::with('inviter','user','event')->where('token',$request->token)->first();
        if(!$invitation){
            return redirect()->route('invitation.response.callback',["msg" => "No Invitation Found"]);
        }

        if($request->response == "Accept"){
            $invitation->status = Constants::INVITATION_ACCEPTED;
            $invitation->save();
            if($invitation->inviter){
                \Mail::to($invitation->inviter)->send(new NotificationInvitationAccepted(
                    $invitation->user->name,
                    $invitation->event->name,
                    $invitation->inviter->name,
                ));
            }
            return redirect()->route('invitation.response.callback',["msg" => "Invitation Accepted"]);
        }else{
            $invitation->status = Constants::INVITATION_DECLINED;
            $invitation->save();
            if($invitation->inviter){
                \Mail::to($invitation->inviter)->send(new NotificationInvitationDeclined(
                    $invitation->user->name,
                    $invitation->event->name,
                    $invitation->inviter->name,
                ));
            }
            return redirect()->route('invitation.response.callback',["msg" => "Invitation Declined"]);
        }
    }

    public function callback(Request $request){
        return view('callback')->with(['msg' => $request->msg]);
    }
}
