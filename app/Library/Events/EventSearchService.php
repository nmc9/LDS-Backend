<?php

namespace App\Library\Events;

use App\Http\Requests\Event\RegisterRequest;
use App\Models\User;

class EventSearchService
{

    public function allUsersEvents($user)
    {
        $events = $user->events;
        $ownedEvents = $user->ownedEvents;

        return $ownedEvents->merge($events);
    }

    public function searchAllUsersEvents($user, $term)
    {
        $events = $this->searchInvitedEvents($user, $term);
        $ownedEvents = $this->searchOwnedEvents($user, $term);

        // Owned Events should be on the left to keep things in order
        return $ownedEvents->merge($events);
    }


    public function searchOwnedEvents($user, $term){
        return $user->ownedEvents()->where("name","LIKE", "%$term%")->orWhere("description","LIKE", "%$term%")->orWhere("location","LIKE", "%$term%")->get();
    }

    public function searchInvitedEvents($user, $term){
        return $user->events()->where("name","LIKE", "%$term%")->orWhere("description","LIKE", "%$term%")->orWhere("location","LIKE", "%$term%")->get();
    }
}
