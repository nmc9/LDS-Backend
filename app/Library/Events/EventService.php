<?php

namespace App\Library\Events;

use App\Http\Requests\Event\RegisterRequest;
use App\Models\User;

class EventService
{

    public function associatedWithEvent($user,$event){
        return $this->ownesEvent($user,$event) || $this->hasEvent($user,$event);
    }


    public function ownesEvent($user,$event){
        return $user->ownedEvents()->where('id',$event->id)->count();

    }

    public function hasEvent($user,$event){
        return $user->events->where('pivot.event_id',$event->id)->count();

    }
}
