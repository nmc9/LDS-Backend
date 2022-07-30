<?php

namespace App\Http\Resources\Bringable;

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
            'assigned_id' => $this->assigned_id,
            'required' => $this->required,
            'acquired' => $this->acquired,  
        ];
    }
}
