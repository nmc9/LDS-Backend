<?php

namespace App\Http\Resources\Bringable;

use App\Http\Resources\Profile\ProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BringableItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bringable_id' => $this->bringable_id,
            'assigned' => new ProfileResource($this->assigned),
            'required' => $this->required,
            'acquired' => $this->acquired,  
        ];
    }
}
