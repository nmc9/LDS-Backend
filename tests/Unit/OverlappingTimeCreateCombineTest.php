<?php

namespace Tests\Unit;

use App\Library\OverlappingTime;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
class OverlappingTimeCreateCombineTest extends TestCase
{
    const DATETIME_FORMAT = "Y-m-d H:i:s";
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_can_combine_time_frames(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 19:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 22:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertEquals([
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 22:00:00")

            ]
        ],$checker->createCombinedTimeFrames([$time_frame_1,$time_frame_2]));


    }

    public function test_can_combine_3_time_frames(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 19:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 22:00:00")
        ];

        $time_frame_3 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 21:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-08-01 23:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertEquals([
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-08-01 23:00:00")

            ]
        ],$checker->createCombinedTimeFrames([$time_frame_1,$time_frame_2,$time_frame_3]));


    }

    public function test_can_combine_first_2_but_not_3_time_frames(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 20:00:00")
        ];

        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 19:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 22:00:00")
        ];

        $time_frame_3 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-08-01 21:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-08-01 23:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertEquals([
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 10:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-01 22:00:00")

            ],
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-08-01 21:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-08-01 23:00:00")
            ]
        ],$checker->createCombinedTimeFrames([$time_frame_1,$time_frame_2,$time_frame_3]));

    }
    public function test_can_combine_last_2_but_not_1_time_frames(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 20:00:00")
        ];

        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 19:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-08-01 22:00:00")
        ];

        $time_frame_3 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-08-01 21:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-08-01 23:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertEquals([
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 20:00:00")
            ],
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 19:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-08-01 23:00:00")
            ]
        ],$checker->createCombinedTimeFrames([$time_frame_1,$time_frame_2,$time_frame_3]));

    }


    public function test_can_combine_1_and_3_but_not_2_time_frames(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 19:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 23:00:00")
        ];

        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2021-06-01 19:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2021-08-01 22:00:00")
        ];

        $time_frame_3 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 20:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertEquals([
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2021-06-01 19:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2021-08-01 22:00:00")
            ],
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 10:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-05-01 23:00:00")
            ],
        ],$checker->createCombinedTimeFrames([$time_frame_1,$time_frame_2,$time_frame_3]));

    }

    /**
     * Example to illustrate
     *  1 2 3 4
     *            6 7 8 9
     *
     *      3 4 5 6 7
     */
    public function test_can_combine_where_first_pass_misses_2_time_frames(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 01:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 04:00:00")
        ];

        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 06:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 09:00:00")
        ];

        $time_frame_3 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 03:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 07:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertEquals([
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 01:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 09:00:00")
            ],
        ],$checker->createCombinedTimeFrames([$time_frame_1,$time_frame_2,$time_frame_3]));

    }

    /**
     * Example to illustrate
     *            6 7 8 9 
     *  1 2 3 4
     *
     *      3 4 5 6 7
     */
    public function test_can_combine_where_first_pass_misses_2_revers_time_frames(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 06:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 09:00:00")
        ];

        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 01:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 04:00:00")
        ];

        $time_frame_3 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 03:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 07:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertEquals([
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 01:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 09:00:00")
            ],
        ],$checker->createCombinedTimeFrames([$time_frame_1,$time_frame_2,$time_frame_3]));

    }




    /**
     * Convoluted Example to show that any scenario can work.
     *                6 7 8 9
     *   1 2 3 4
     *                           11 12 13
     *     2 3 4             
     *                    8 9 10 11    
     *   
     */
    public function test_can_combine_very_convoluted_senario_time_frames(){
        $time_frame_1 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 06:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 09:00:00")
        ];

        $time_frame_2 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 01:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 04:00:00")
        ];

        $time_frame_3 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 11:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 13:00:00")
        ];

        $time_frame_4 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 02:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 04:00:00")
        ];

        $time_frame_5 = [
            "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 08:00:00"),
            "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 11:00:00")
        ];

        $checker = new OverlappingTime();

        $this->assertEquals([
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 01:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 04:00:00")
            ],
            [
                "start" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 06:00:00"),
                "end" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-06-01 13:00:00")
            ],
        ],$checker->createCombinedTimeFrames([$time_frame_1,$time_frame_2,$time_frame_3,$time_frame_4,$time_frame_5]));

    }

}
