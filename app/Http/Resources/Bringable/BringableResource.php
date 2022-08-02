<?php

namespace App\Http\Resources\Bringable;

use App\Http\Resources\Bringable\BringableItemCollection;
use App\Library\Bringables\BringableService;
use Illuminate\Http\Resources\Json\JsonResource;

class BringableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $service = resolve(BringableService::class);

        $required = $this->items->sum('required');
        $acquired = $this->items->sum('acquired');

        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'name' => $this->name,
            'notes' => $this->notes,
            'importance' => $this->importance,
            'items' => new BringableItemCollection($this->items),
            'required_count' => $required,
            'acquired_count' => $acquired,
            'acquired_all' => $service->isAcquiredAll($required,$acquired),
        ];
    }



}
