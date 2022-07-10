<?php

namespace App\Http\Resources\Event;

use App\Library\Constants;
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
                'start' => $this->start_datetime->format(Constants::DATETIME_READABLE_SHORT),
                'end' => $this->end_datetime->format(Constants::DATETIME_READABLE_SHORT),
            ],
            'time_frame' => [
                'start' => $this->start_datetime->format(Constants::DATETIME_FORMAT),
                'end' => $this->end_datetime->format(Constants::DATETIME_FORMAT),
            ],
            'owner_id' => $this->owner_id,
        ];
    }
}
