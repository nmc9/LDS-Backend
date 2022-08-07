<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bringable\UpdateBringableItemRequest;
use App\Http\Resources\Bringable\BringableItemCollection;
use App\Http\Resources\Bringable\BringableItemResource;
use App\Library\Bringables\BringableItemService;
use App\Models\Bringable;
use App\Models\BringableItem;
use Illuminate\Http\Request;

class BringableItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    
    public function index(Bringable $bringable){

        return new BringableItemCollection($bringable->items);
    }
    public function create(Bringable $bringable){

        return new BringableItemResource(BringableItem::factory()->for($bringable)->create());
    }
    public function reassign(Request $request, BringableItem $bringable_item){

        return new BringableItemResource($bringable_item);

    }
    public function update(UpdateBringableItemRequest $request, BringableItem $bringable_item, BringableItemService $service){

        return new BringableItemResource(
            $service->update($bringable_item,$request)
        );
    }
    public function destroy(Request $request, BringableItem $bringable_item){

        return response()->json([
            'message' => 'Success'
        ]);

    }
}
