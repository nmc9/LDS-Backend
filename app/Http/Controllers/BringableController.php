<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bringable\CreateBringableRequest;
use App\Http\Resources\Bringable\BringableResource;
use App\Library\Bringables\BringableService;
use App\Models\Bringable;
use App\Models\Event;
use Illuminate\Http\Request;

class BringableController extends Controller
{
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


}
