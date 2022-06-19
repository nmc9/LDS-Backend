<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            'start_datetime' => $this->start_datetime->format('Y-m-d H:i:s'),
            "end_datetime" => $this->end_datetime->format('Y-m-d H:i:s'),
            'owner_id' => $this->owner_id,
        ];
    }
}
