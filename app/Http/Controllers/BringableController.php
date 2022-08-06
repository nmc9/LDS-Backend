<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bringable\CreateBringableRequest;
use App\Http\Resources\Bringable\BringableCollection;
use App\Http\Resources\Bringable\BringableResource;
use App\Library\Bringables\BringableService;
use App\Models\Bringable;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class BringableController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request, Event $event, BringableService $service){

        if($request->acquired == null){
            $bringables = $service->listAllBringables($event,$request->search);
        }else{
            $bringables = $request->acquired ? 
            $service->listAcquiredBringables($event,$request->search)
            : $service->listNotAcquiredBringables($event,$request->search);

        }

        return new BringableCollection($bringables);

    }

    public function user_index(Request $request, Event $event, User $user, BringableService $service){

        if($request->acquired == null){
            $bringables = $service->listUserBringables($user,$event,$request->search);
        }else{
            $bringables = $request->acquired ? 
            $service->listAcquiredUserBringables($user,$event,$request->search)
            : $service->listNotAcquiredUserBringables($user,$event,$request->search);

        }

        return new BringableCollection($bringables);

    }




    public function store(CreateBringableRequest $request, Event $event, BringableService $service){

        $this->authorize('create', [Bringable::class,$event]);

        return new BringableResource(
            $service->create($event,
                $request->only(['name',
                    'notes',
                    'importance',
                    'assigned_id',
                    'required',
                    'acquired'
                ])
            )
        );
    }

    public function show(Event $event, Bringable $bringable){
        return new BringableResource($bringable->load(['items','items.assigned']));
    }

    public function update(Request $request, Bringable $bringable){

        $bringable->load('items');
        /* TODO */
        return new BringableResource(
            $bringable
        );
    }

    public function destory(Request $request, Bringable $bringable){

        /* TODO */

        return response()->json([
            'message' => 'Success'
        ]);
    }

    public function clearaquired(Request $request, Bringable $bringable){
        /* TODO */

        return response()->json([
            'message' => 'Success'
        ]);
    }


}
