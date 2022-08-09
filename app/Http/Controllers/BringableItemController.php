<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bringable\CreateBringableItemRequest;
use App\Http\Requests\Bringable\ReassignBringableItemRequest;
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
    public function create(CreateBringableItemRequest $request, Bringable $bringable, BringableItemService $service){

        return new BringableItemResource(
            $service->create($bringable,$request)
        );
    }
    public function reassign(ReassignBringableItemRequest $request, BringableItem $bringable_item, BringableItemService $service){

        $item = $service->reassignTo($bringable_item,$request->assigned_id,$request->keep ?? false);
        return new BringableItemResource($item);
    }
    public function update(UpdateBringableItemRequest $request, BringableItem $bringable_item, BringableItemService $service){

        return new BringableItemResource(
            $service->update($bringable_item,$request)
        );
    }
    public function destroy(BringableItem $bringable_item, BringableItemService $service){

        $service->delete($bringable_item);
        
        return response()->json([
            'message' => 'Success'
        ]);

    }
}
