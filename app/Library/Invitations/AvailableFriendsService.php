<?php

namespace App\Library\Invitations;

use App\Exceptions\AvailabilityCalculationException;
use App\Http\Requests\Profile\RegisterRequest;
use App\Library\Constants;
use App\Library\OverlappingTime;
use App\Library\Profiles\FriendService;
use App\Mail\InvitationReminderMail;
use App\Mail\InvitationRequestMail;
use App\Models\Friend;
use App\Models\Invitation;
use App\Models\User;
use Carbon\Carbon;

class AvailableFriendsService
{
    private $overlappingTime;

    public function __construct(OverlappingTime $overlappingTime)
    {
        $this->overlappingTime = $overlappingTime;
    }

    public function listAvailable($friends,$event){

        $event_time_frame = $this->eventToTimeFrame($event);

        return $friends->filter(function($friend) use ($event_time_frame){
            $av_time_frames = $this->availabilityToTimeFrame($friend->availabilities,$event_time_frame);
            return $this->overlappingTime->isOverlapping($event_time_frame,$av_time_frames);
        });

    }


    /**
     * This function determines that start and end date of the event
     * It then loops through the days to convert the availability to an actual datetime
     * If nextDayFix is true this function will also convert 23:55:00 and greater to the beginning of the next day
     */
    public function availabilityToTimeFrame($availabilities,$event_time_frame,$nextDayFix = true){

        $time_frames = [];
        $day = (clone $event_time_frame["start"])->subDays(1);
        do {
            $day->addDays(1);
            $av = $availabilities->where('day_of_week',strtolower($day->englishDayOfWeek))->first();
            if($av){
                $time_frames[] = [
                    "start" => $this->_availabilityToTimeFrame($av->start_time,$day,false),
                    "end" => $this->_availabilityToTimeFrame($av->end_time,$day,$nextDayFix),
                ];
            }
        } while (!$day->isSameDay($event_time_frame["end"]));


        return $time_frames;
    }

    private function _availabilityToTimeFrame($datetime,$day,$nextDayFix){
        if($nextDayFix){
            if($datetime->hour == 23 && $datetime->minute >= 55){
                return ((clone $day)->addDays(1)->startOfDay());
            }
        }
        return ((clone $day)->setTimeFromTimeString($datetime->format("H:i:s")));
    }

    public function eventToTimeFrame($event){
        $this->limitEventLengthForAvailabilitySearch($event);

        return [
            "start" => $event->start_datetime,
            "end" => $event->end_datetime,
        ];

    }

    public function limitEventLengthForAvailabilitySearch($event){
        if($event->start_datetime > $event->end_datetime){
            throw new AvailabilityCalculationException("Event must have end date to search by Availability");
        }
        if($event->start_datetime->diffInDays($event->end_datetime) > 30){
            throw new AvailabilityCalculationException("Event is too long to search by Availability");
        }
    }

}
