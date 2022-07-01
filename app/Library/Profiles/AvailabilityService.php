<?php

namespace App\Library\Profiles;

use App\Http\Requests\Profile\RegisterRequest;
use App\Models\Availability;
use App\Models\User;
use Carbon\Carbon;

class AvailabilityService
{

    const START_FALLBACK = "00:00:00";
    const END_FALLBACK = "23:59:00";

    public function store($user,$data)
    {
        $avs = [];
        foreach($data as $day_of_week => $range){
            if($this->shouldSkip($range)){
                continue;
            }

            $av = new Availability;
            if(isset($range['start']) && $range["start"] !== []){
                $av->start_time = Carbon::createFromTime($range["start"]["hours"],$range["start"]["minutes"])->format("H:i:s");
            }else{
                $av->start_time = static::START_FALLBACK;
            }

            if(isset($range['end']) && $range["end"] !== []){
                $av->end_time = Carbon::createFromTime($range["end"]["hours"],$range["end"]["minutes"])->format("H:i:s");
            }else{
                $av->end_time = static::END_FALLBACK;
            }

            $av->day_of_week = $day_of_week;

            $av->user_id = $user->id;

            $av->save();
            $avs[] = $av;

        }
        return $avs;
    }

    public function listForUser($user){
        return $user->availabilities->keyBy('day_of_week')->map(function($item){
            return [
                "start" => ["hours" => (int) $item->start_time->format("H"), "minutes" => (int) $item->start_time->format("i")],
                "end" => ["hours" => (int) $item->end_time->format("H"), "minutes" => (int) $item->end_time->format("i")],
            ];
        });
    }

    private function shouldSkip($range){
        return !(
            (isset($range['start']) && $range["start"] !== []) || (isset($range['end']) && $range["end"] !== [])
        );

    }


}
