<?php

namespace App\Library\Bringables;

use App\Http\Requests\Event\RegisterRequest;
use App\Models\Bringable;
use App\Models\BringableItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class BringableItemService
{

    public function update($bringable_item, $bringableItemObject){

        $bringable_item->update([
            'required' => $bringable_item->required >= 0 ? $bringableItemObject->required : -1,
            'acquired' => $bringableItemObject->acquired,
        ]);

        return $bringable_item;

    }

    public function reassignTo($bringable_item, $assigned_id, $keep = false){
        if($assigned_id < 0){
            $assigned_id = null;
        }

        if($keep){
            $bringable_item->update([
                'assigned_id' => $assigned_id,

            ]);
        }else{
            $bringable_item->update([
                'assigned_id' => $assigned_id,
                'acquired' => 0
            ]);
        }


        return $bringable_item;

    }

    public function create($bringable, $bringableItemObject){
        $isUnlimited = $this->isBringableUnlimited($bringable);
        return BringableItem::create([
            'bringable_id' => $bringable->id,
            'assigned_id' => $bringableItemObject->assigned_id,
            'required' => $isUnlimited ? -1 : $bringableItemObject->required,
            'acquired' => $bringableItemObject->acquired,
        ]);

    }

    public function delete($bringable_item){
        return $bringable_item->delete();
    }


    public function isBringableUnlimited($bringable){
        $firstBringableItem = $bringable->items->first();
        if($firstBringableItem){
            return $firstBringableItem->required === -1;
        }
        return false;
    }
}
