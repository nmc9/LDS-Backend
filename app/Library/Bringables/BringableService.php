<?php

namespace App\Library\Bringables;

use App\Http\Requests\Event\RegisterRequest;
use App\Models\Bringable;
use App\Models\BringableItem;
use App\Models\User;

class BringableService
{

    public function create($event, $data){

        $rootBringable = $this->createRootBringable($event->id,$data);

        return $this->createBringableItem($rootBringable,$data);

    }

    public function createRootBringable($event,$data){
        if($event instanceOf Event){
            $event = $event->id;
        }
        return Bringable::create([
            'event_id' => $event,
            'name' => $data['name'],
            'notes' => $data['notes'],
            'importance' => $data['importance'],
        ]);

    }

    public function createBringableItem($bringable,$data){
        if($bringable instanceOf Bringable){
            $bringable = $bringable->id;
        }
        return BringableItem::create([
            'bringable_id' => $bringable,
            'assigned_id' => $data['assigned_id'],
            'required' => $data['required'],
            'acquired' => $data['acquired'],
        ]);
    }



}
