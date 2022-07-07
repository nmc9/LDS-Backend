<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\CreateFriendRequest;
use App\Http\Resources\Profile\FriendResource;
use App\Mail\FriendRequestMail;
use App\Mail\NotificationFriendRequestMail;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFriendRequest $request)
    {
        $friendUser = User::findOrFail($request->user_id);
        $requestingUser = \Auth::user();

        $token = \Str::random(60);

        $friend = Friend::create([
            'from_user_id' => $requestingUser->id,
            'to_user_id' => $request->user_id,
            'token' => $token
        ]);

        \Mail::to($requestingUser)->send(new NotificationFriendRequestMail(
            $friendUser->name,
            $requestingUser->name,
        ));

        \Mail::to($friendUser)->send(new FriendRequestMail(
            $requestingUser->name,
            $friendUser->name,
            route('friend.response') . "?token=" . $token . "&response=Accept",
            route('friend.response') . "?token=" . $token . "&response=Decline",
        ));

        return response()->json([
            'msg' => 'Success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function show(Friend $friend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Friend $friend)
    {
        //
    }
}
