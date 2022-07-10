<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\CreatImaginaryFriendRequest;
use App\Mail\ImaginaryFriendRequestMail;
use App\Mail\NotificationImaginaryFriendRequestMail;
use App\Models\ImaginaryFriend;
use Illuminate\Http\Request;

class ImaginaryFriendController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatImaginaryFriendRequest $request)
    {
        $requestingUser = \Auth::user();

        $token = \Str::random(60);

        $friend = ImaginaryFriend::create([
            'from_user_id' => $requestingUser->id,
            'to_user_email' => $request->email,
            'token' => $token
        ]);

        \Mail::to($requestingUser)->send(new NotificationImaginaryFriendRequestMail(
            $request->email,
            $requestingUser->name,
        ));

        \Mail::to($request->email)->send(new ImaginaryFriendRequestMail(
            $requestingUser->name,
            $request->email,
            route('friend.iresponse') . "?token=" . $token . "&response=Accept",
            route('friend.iresponse') . "?token=" . $token . "&response=Decline",
        ));

        return response()->json([
            'message' => 'Success'
        ]);
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImaginaryFriend  $imaginaryFriend
     * @return \Illuminate\Http\Response
     */
    public function show(ImaginaryFriend $imaginaryFriend)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ImaginaryFriend  $imaginaryFriend
     * @return \Illuminate\Http\Response
     */
    public function edit(ImaginaryFriend $imaginaryFriend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImaginaryFriend  $imaginaryFriend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImaginaryFriend $imaginaryFriend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImaginaryFriend  $imaginaryFriend
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImaginaryFriend $imaginaryFriend)
    {
        //
    }
}
