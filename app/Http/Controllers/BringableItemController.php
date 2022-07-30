<?php

namespace App\Http\Controllers;

use App\Http\Resources\Bringable\BringableItemCollection;
use App\Http\Resources\Bringable\BringableItemResource;
use App\Models\Bringable;
use App\Models\BringableItem;
use Illuminate\Http\Request;

class BringableItemController extends Controller
{

    public function index(Bringable $bringable){

        return new BringableItemCollection($bringable->items);
    }
    public function create(Bringable $bringable){

        return new BringableItemResource(BringableItem::factory()->for($bringable)->create());
    }
    public function reassign(Request $request, BringableItem $item){

        return new BringableItemResource($item);

    }
    public function update(Request $request, BringableItem $item){

        return new BringableItemResource($item);
    }
    public function destroy(Request $request, BringableItem $item){

        return response()->json([
            'message' => 'Success'
        ]);

    }
}
