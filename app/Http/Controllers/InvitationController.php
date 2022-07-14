<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invitation\SendInvitationRequest;
use App\Http\Resources\Profile\ProfileCollection;
use App\Library\Constants;
use App\Library\Invitations\AvailableFriendsService;
use App\Library\Invitations\InvitationMailService;
use App\Library\Invitations\InvitationService;
use App\Library\Profiles\FriendService;
use App\Models\Event;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;

class InvitationController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function listAvailable(Request $request, Event $event, FriendService $friendService, AvailableFriendsService $availService){

        try{
            $usersWithAvailability = $friendService->getFriends(\Auth::user())->load('availabilities');
        }catch(AvailabilityCalculationException $e){
            return response()->json([
                "message" => $e->getMessage(),
            ],500);
        }

        $friends = $availService->listAvailable($usersWithAvailability,$event);

        return new ProfileCollection($friends);

    }
    
    public function store(SendInvitationRequest $request, Event $event, FriendService $friendService, InvitationService $service, InvitationMailService $mailService){

        $results = $service->createInvitations($friendService,
            $request->users,
            $event->id,
            \Auth::user(),
        );

        $mailService->sendBasedOnCreatedInviations(
            $results,
            $event,
            \Auth::user(),
        );


        $jsonResult = collect($results)->map(function($result){
            return [ $result[1]->id,$result[0] ];
        });


        return response()->json([
            'message' => 'Success',
            'results' => $jsonResult
        ]);

    }


    public function update(Request $request, Event $event, Invitation $invitation){

    }


    
}
