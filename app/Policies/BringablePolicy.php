<?php

namespace App\Policies;

use App\Library\Events\EventService;
use App\Models\Bringable;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BringablePolicy
{
    use HandlesAuthorization;

    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bringable  $bringable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Bringable $bringable)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Event $event)
    {
        return $this->eventService->associatedWithEvent($user,$event);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bringable  $bringable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Bringable $bringable)
    {
        return $this->eventService->associatedWithEvent($user,$bringable->event);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bringable  $bringable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Bringable $bringable)
    {
        return $this->eventService->associatedWithEvent($user,$bringable->event);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bringable  $bringable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Bringable $bringable)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bringable  $bringable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Bringable $bringable)
    {
        //
    }
}
