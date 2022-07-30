<?php

namespace App\Library\Bringables;

use App\Http\Requests\Event\RegisterRequest;
use App\Models\Bringable;
use App\Models\BringableItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class BringableService
{

    public function listUserBringables($user,$event,$search = null){

        $bringables = $search ? $event->bringables()
        ->where('name','LIKE',"%$search%")
        ->orWhere('notes','LIKE',"%$search%")
        ->get()
        : $event->bringables;


        $bringables = $event->bringables()->with(['items' => function ($query) use ($user) {
            return $query->where('assigned_id', $user->id);
        }])
        ->whereHas('items', function (Builder $query) use ($user) {
            $query->where('assigned_id', $user->id);
        })->get();

        return $bringables;


    }


    public function listAllBringables($event,$search = null){

        $bringables = $search ? $event->bringables()
        ->where('name','LIKE',"%$search%")
        ->orWhere('notes','LIKE',"%$search%")
        ->get()
        : $event->bringables;

        
        $bringables->load('items');
        return $bringables;
    }


    public function listNotAcquiredBringables($event,$search = null){

        $bringables = $this->listAllBringables($event,$search);

        return $bringables->filter(function($bringable){
            return !$this->isBringableAcquiredAll($bringable);
        });
    }

    public function listAcquiredBringables($event,$search = null){

        $bringables = $this->listAllBringables($event,$search);
        return $bringables->filter(function($bringable){
            return $this->isBringableAcquiredAll($bringable);
        });
    }

    public function listNotAcquiredUserBringables($user, $event,$search = null){

        $bringables = $this->listUserBringables($user,$event,$search);

        return $bringables->filter(function($bringable){
            return !$this->isBringableAcquiredAll($bringable);
        });
    }

    public function listAcquiredUserBringables($user, $event,$search = null){

        $bringables = $this->listUserBringables($user,$event,$search);
        return $bringables->filter(function($bringable){
            return $this->isBringableAcquiredAll($bringable);
        });
    }


    public function isBringableAcquiredAll($bringable){
        return $this->isAcquiredAll($bringable->items->sum('required'),$bringable->items->sum('acquired'));
    }

    public function isAcquiredAll($required, $acquired){
        if($acquired <= 0){
            return false;
        }
        return $acquired >= $required;
    }

    public function create($event, $data){

        $rootBringable = $this->createRootBringable($event->id,$data);

        $this->createBringableItem($rootBringable,$data);

        return $rootBringable->load('items');

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
