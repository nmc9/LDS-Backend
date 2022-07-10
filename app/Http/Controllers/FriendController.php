<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\CreateFriendRequest;
use App\Http\Resources\Profile\FriendResource;
use App\Http\Resources\Profile\ProfileCollection;
use App\Library\Profiles\FriendMailService;
use App\Library\Profiles\FriendService;
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
    public function index(Request $request, FriendService $service)
    {
        if($request->includeUnaccepted){
            $friends = $service->getFriendsWithUnaccepted(\Auth::user());
        }else{
            $friends = $service->getFriends(\Auth::user());            
        }

        return new ProfileCollection($friends);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFriendRequest $request, FriendService $service, FriendMailService $mailService)
    {
        $friendUser = User::findOrFail($request->user_id);
        $requestingUser = \Auth::user();

        $token = $service->addFriend($requestingUser->id,$friendUser->id);

        $mailService->sendRequestNotification($requestingUser,$friendUser);
        $mailService->sendRequestMail($requestingUser,$friendUser,$token);

        return response()->json([
            'message' => 'Success'
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
