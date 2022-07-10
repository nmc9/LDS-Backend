<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\Event\EventCollection;
use App\Http\Resources\Event\EventResource;
use App\Http\Resources\Event\EventSearchCollection;
use App\Library\Events\EventSearchService;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->authorizeResource(Event::class, 'event');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EventSearchService $service)
    {
        if($request->search){
            $events = $service->searchAllUsersEvents($request->user(),$request->search);
        }else{
            $events = $service->allUsersEvents($request->user());
        }

        return new EventSearchCollection($events); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEventRequest $request)
    {
        return new EventResource(Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'start_datetime' => $request->start_datetime,
            "end_datetime" => $request->end_datetime,
            'owner_id' => $request->user()->id
        ])); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event  $event)
    {
        $event->update([
            "description" => $request->description,
            "location" => $request->location,
            "start_datetime" => $request->start_datetime,
            "end_datetime" => $request->end_datetime,
        ]);
        return new EventResource($event);
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     //
    // }
}
