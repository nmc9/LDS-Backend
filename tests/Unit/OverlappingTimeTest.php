<?php

namespace Tests\Unit;

use App\Library\OverlappingTime;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
class OverlappingTimeTest extends TestCase
{
    const DATETIME_FORMAT = "Y-m-d H:i:s";
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_if_time_is_fully_in_time_frame_then_return_true()
    {
        $time_frame = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_array = [
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 09:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 21:00:00")
            ]
        ];

        $checker = new OverlappingTime();

        $this->assertTrue($checker->isOverlapping($time_frame,$time_frame_array));
    }

    public function test_if_time_frame_is_exactly_in_time_frame_array_then_return_true()
    {
        $time_frame = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_array = [
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
            ]
        ];

        $checker = new OverlappingTime();

        $this->assertTrue($checker->isOverlapping($time_frame,$time_frame_array));
    }

    public function test_if_time_frame_start_is_after_then_return_false()
    {
        $time_frame = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_array = [
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 11:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 21:00:00")
            ]
        ];

        $checker = new OverlappingTime();

        $this->assertFalse($checker->isOverlapping($time_frame,$time_frame_array));
    }

    public function test_if_time_frame_end_is_before_then_return_false()
    {
        $time_frame = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_array = [
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 09:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 19:00:00")
            ]
        ];

        $checker = new OverlappingTime();

        $this->assertFalse($checker->isOverlapping($time_frame,$time_frame_array));
    }

    public function test_if_time_frame_start_is_after_and_end_is_before_then_return_false()
    {
        $time_frame = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_array = [
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 11:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 19:00:00")
            ]
        ];

        $checker = new OverlappingTime();

        $this->assertFalse($checker->isOverlapping($time_frame,$time_frame_array));
    }

    public function test_if_time_frame_is_fully_in_any_then_return_true()
    {
        $time_frame = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_array = [
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-04-01 09:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-04-01 21:00:00")
            ],
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 09:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 21:00:00")
            ]
        ];

        $checker = new OverlappingTime();

        $this->assertTrue($checker->isOverlapping($time_frame,$time_frame_array));
    }

    public function test_if_time_frame_is_not_fully_in_any_then_return_false()
    {
        $time_frame = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-04-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-04-01 20:00:00")
        ];

        $time_frame_array = [
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-09-01 09:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-09-01 21:00:00")
            ]
        ];

        $checker = new OverlappingTime();

        $this->assertFalse($checker->isOverlapping($time_frame,$time_frame_array));
    }

    public function test_if_time_frame_is_within_a_combined_then_return_true(){
        $time_frame = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_array = [
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 09:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 13:00:00")
            ],
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 11:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 21:00:00")
            ]
        ];

        $checker = new OverlappingTime();

        $this->assertTrue($checker->isOverlapping($time_frame,$time_frame_array));
    }

    public function test_if_time_frame_is_between_two_sequenced_time_frames_then_return_true(){

        $time_frame = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_array = [
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 05:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 13:00:00")
            ],
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 11:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 21:00:00")
            ]
        ];

        $checker = new OverlappingTime();

        $this->assertTrue($checker->isOverlapping($time_frame,$time_frame_array));

    }


}
