<?php

namespace Tests\Feature\Events;

use App\Models\Availability;
use App\Models\Event;
use App\Models\Friend;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanViewAvailableFriendsTest extends TestCase
{
    use RefreshDatabase;

    const DATETIME_FORMAT = "Y-m-d H:i:s";

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_view_available_friends_to_an_event()
    {

        $user = User::factory()->create([
            "name" => "Sender"
        ]);

        $otherUser = User::factory()->create([
            "name" => "Receiver"
        ]);

        $availability = Availability::factory()->create([
            'user_id' => $otherUser->id,
            'day_of_week' => 'tuesday',
            'start_time' => "00:00:00",
            'end_time' => "23:55:00",
        ]);
        Availability::factory()->create([
            'user_id' => $otherUser->id,
            'day_of_week' => 'wednesday',
            'start_time' => "00:00:00",
            'end_time' => "23:55:00",
        ]);

        Friend::factory()->create([
            "from_user_id" => $user->id,
            "to_user_id" => $otherUser->id,
            "accepted" => true,
        ]);

        $event = Event::factory()->create(        [
            "start_datetime" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-19 10:00:00"), //Tuesday
            "end_datetime" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-20 21:00:00") //Wednesday
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/' . $event->id . '/available/');

 
        $response->assertStatus(200);

        $response->assertJson([
            "data" => [
                [
                    'id' => $otherUser->id,
                    'name' => "Receiver",
                    'email' => $otherUser->email,
                ]
            ],
        ]);

    }

    public function test_can_view_available_friends_to_an_event_weekend()
    {

        $user = User::factory()->create([
            "name" => "Sender"
        ]);

        $otherUser = User::factory()->create([
            "name" => "Receiver"
        ]);
        Availability::factory()->create([
            'user_id' => $otherUser->id,
            'day_of_week' => 'friday',
            'start_time' => "00:00:00",
            'end_time' => "23:55:00",
        ]);
        Availability::factory()->create([
            'user_id' => $otherUser->id,
            'day_of_week' => 'saturday',
            'start_time' => "00:00:00",
            'end_time' => "23:55:00",
        ]);
        Availability::factory()->create([
            'user_id' => $otherUser->id,
            'day_of_week' => 'sunday',
            'start_time' => "00:00:00",
            'end_time' => "23:55:00",
        ]);

        Friend::factory()->create([
            "from_user_id" => $user->id,
            "to_user_id" => $otherUser->id,
            "accepted" => true,
        ]);

        $event = Event::factory()->create(        [
            "start_datetime" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-15 00:00:00"), //Friday
            "end_datetime" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-18 00:00:00") //Monday (Sunday aty Midnight)
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/' . $event->id . '/available/');

        $response->assertStatus(200);

        $response->assertJson([
            "data" => [
                [
                    'id' => $otherUser->id,
                    'name' => "Receiver",
                    'email' => $otherUser->email,
                ]
            ],
        ]);

    }

    public function test_cant_view_unavailable_friends_to_an_event_distinct()
    {

        $user = User::factory()->create([
            "name" => "Sender"
        ]);

        $otherUser = User::factory()->create([
            "name" => "Receiver"
        ]);

        $availability = Availability::factory()->create([
            'user_id' => $otherUser->id,
            'day_of_week' => 'tuesday',
            'start_time' => "00:00:00",
            'end_time' => "23:55:00",
        ]);
        Availability::factory()->create([
            'user_id' => $otherUser->id,
            'day_of_week' => 'wednesday',
            'start_time' => "02:00:00", // This is not continous with Tuesday (Double check this gap with unit tests)
            'end_time' => "23:55:00",
        ]);

        Friend::factory()->create([
            "from_user_id" => $user->id,
            "to_user_id" => $otherUser->id,
            "accepted" => true,
        ]);

        $event = Event::factory()->create(        [
            "start_datetime" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-19 10:00:00"), //Tuesday
            "end_datetime" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-20 21:00:00") //Wednesday
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/' . $event->id . '/available/');

        $response->assertStatus(200);

        $response->assertJson([
            "data" => [
            ],
        ]);

    }


    public function test_()
    {

        $user = User::factory()->create([
            "name" => "Sender"
        ]);

        $otherUser = User::factory()->create([
            "name" => "Receiver"
        ]);

        $availability = Availability::factory()->create([
            'user_id' => $otherUser->id,
            'day_of_week' => 'tuesday',
            'start_time' => "00:00:00",
            'end_time' => "23:55:00",
        ]);
        Availability::factory()->create([
            'user_id' => $otherUser->id,
            'day_of_week' => 'wednesday',
            'start_time' => "02:00:00", // This is not continous with Tuesday
            'end_time' => "23:55:00",
        ]);

        Friend::factory()->create([
            "from_user_id" => $user->id,
            "to_user_id" => $otherUser->id,
            "accepted" => true,
        ]);

        $event = Event::factory()->create(        [
            "start_datetime" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-19 10:00:00"), //Tuesday
            "end_datetime" => Carbon::createFromFormat(static::DATETIME_FORMAT,"2022-07-20 21:00:00") //Wednesday
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/' . $event->id . '/available/');

        $response->assertStatus(200);

        $response->assertJson([
            "data" => [
            ],
        ]);

    }

}
