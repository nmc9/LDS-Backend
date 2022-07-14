<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\ImaginaryFriend;
use Illuminate\Http\Request;

class WebFriendResponseController extends Controller
{

    public function response(Request $request)
    {
        if(!$request->token){
            return redirect()->route('friend.response.callback',["msg" => "No Friend Request Found"]);
        }

        $friend = Friend::where('token',$request->token)->first();
        if(!$friend){
            return redirect()->route('friend.response.callback',["msg" => "No Friend Request Found"]);
        }

        if($request->response == "Accept"){
            $friend->accepted = true;
            $friend->save();
            return redirect()->route('friend.response.callback',["msg" => "Friend Added"]);
        }else{
            $friend->delete();
            return redirect()->route('friend.response.callback',["msg" => "Friend Request Removed"]);
        }
    }


    public function iresponse(Request $request)
    {
        if(!$request->token){
            return redirect()->route('friend.response.callback',["msg" => "No Friend Request Found"]);
        }
        $friend = ImaginaryFriend::where('token',$request->token)->first();
        if(!$friend){
            return redirect()->route('friend.response.callback',["msg" => "No Friend Request Found"]);
        }

        if($request->response == "Accept"){
            $friend->accepted = true;
            $friend->save();
            return redirect()->route('friend.response.callback',["msg" => "Friend Added"]);
        }else{
            $friend->delete();
            return redirect()->route('friend.response.callback',["msg" => "Friend Request Removed"]);
        }
    }

    public function callback(Request $request){
        return view('callback')->with(['msg' => $request->msg]);
    }
}
