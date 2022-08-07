<?php

namespace App\Library\Bringables;

use App\Http\Requests\Event\RegisterRequest;
use App\Models\Bringable;
use App\Models\BringableItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class BringableItemService
{

    public function update($bringable_item, $bringableObject){

        $bringable_item->update([
            'required' => $bringable_item->required >= 0 ? $bringableObject->required : -1,
            'acquired' => $bringableObject->acquired,
        ]);

        return $bringable_item;

    }
}
