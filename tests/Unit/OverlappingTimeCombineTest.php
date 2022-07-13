<?php

namespace Tests\Unit;

use App\Library\OverlappingTime;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
class OverlappingTimeCombineTest extends TestCase
{
    const DATETIME_FORMAT = "Y-m-d H:i:s";
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_if_time_frame_1_is_fully_within_another_then_they_are_overlapping()
    {
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertTrue($checker->areOverlappingTimeFrames($time_frame_1,$time_frame_2));
        $this->assertEquals([
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ],$checker->combineTimeFrames($time_frame_1,$time_frame_2));
    }

    public function test_if_time_frame_2_is_fully_within_another_then_they_are_overlapping()
    {

        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ];
        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertTrue($checker->areOverlappingTimeFrames($time_frame_1,$time_frame_2));
        $this->assertEquals([
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ],$checker->combineTimeFrames($time_frame_1,$time_frame_2));
    }


    public function test_if_time_frame_1_end_point_is_in_time_frame_2_then_they_are_overlapping(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ];
        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 19:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 21:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertTrue($checker->areOverlappingTimeFrames($time_frame_1,$time_frame_2));
        $this->assertEquals([
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 21:00:00")
        ],$checker->combineTimeFrames($time_frame_1,$time_frame_2));
    }

    public function test_if_time_frame_1_start_point_is_in_time_frame_2_then_they_are_overlapping(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ];
        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 09:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 11:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertTrue($checker->areOverlappingTimeFrames($time_frame_1,$time_frame_2));
        $this->assertEquals([
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 09:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ],$checker->combineTimeFrames($time_frame_1,$time_frame_2));
    }

    public function test_if_time_frame_2_end_point_is_in_time_frame_1_then_they_are_overlapping(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 19:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 21:00:00")
        ];
        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertTrue($checker->areOverlappingTimeFrames($time_frame_1,$time_frame_2));
        $this->assertEquals([
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 21:00:00")
        ],$checker->combineTimeFrames($time_frame_1,$time_frame_2));
    }

    public function test_if_time_frame_2_start_point_is_in_time_frame_1_then_they_are_overlapping(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 09:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 11:00:00")
        ];
        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertTrue($checker->areOverlappingTimeFrames($time_frame_1,$time_frame_2));
        $this->assertEquals([
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 09:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ],$checker->combineTimeFrames($time_frame_1,$time_frame_2));
    }

    /**
     * This test should still combine two time frames. Since there are some use cases 
     * where we want to combine datetimes that are very close
     */
    public function test_if_time_frame_is_distinct_another_then_they_arent_overlapping()
    {
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertFalse($checker->areOverlappingTimeFrames($time_frame_1,$time_frame_2));
        $this->assertEquals([
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 20:00:00")
        ],$checker->combineTimeFrames($time_frame_1,$time_frame_2));

    }

}
