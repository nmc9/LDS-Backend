<?php

namespace App\Library\Bringables;

use App\Http\Requests\Event\RegisterRequest;
use App\Models\Bringable;
use App\Models\BringableItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class BringableManageService
{

    public function update($bringable, $data){
        $bringable->update($data);

        $bringable->load(['items','items.assigned']);

        return $bringable;

    }

    public function delete($bringable){
        $bringable->delete();

        return true;
    }

}
