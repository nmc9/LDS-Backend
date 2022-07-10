<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;

class EventSearchResource extends JsonResource
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
            'name' => $this->name,
            'location' => $this->location,
            'description' => $this->description,
            'time_frame_text' => [
                'start' => $this->start_datetime->toDayDateTimeString(),
                'end' => $this->end_datetime->toDayDateTimeString(),
            ],
            "time_frame_short" => [
                'start' => $this->start_datetime->format('M jS, Y, g:ia'),
                'end' => $this->end_datetime->format('M jS, Y, g:ia'),
            ],
            'time_frame' => [
                'start' => $this->start_datetime->format('Y-m-d H:i:s'),
                'end' => $this->end_datetime->format('Y-m-d H:i:s'),
            ],
            'owner_id' => $this->owner_id,
        ];
    }

    // July 9, 2022, 1:30pm – July 16, 2022, 3:00pm
}
