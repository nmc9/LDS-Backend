<?php

namespace Tests\Feature\Events;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanSearchEventsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_list_all_of_their_events()
    {

        $user = User::factory()->create();

        //Invited and Owned Event
        $event = Event::factory()->hasAttached($user)->create([
            "owner_id" => $user->id,
        ]);

        //Owned Event but not invited
        $event2 = Event::factory()->create([
            "owner_id" => $user->id,
        ]);

        //Invited but not owned
        $event3 = Event::factory()->hasAttached($user)->create();

        //Niether
        $event4 = Event::factory()->create([
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/');

        $response->assertStatus(200); 

        $response->assertJson([
            "data" => [
                [
                    'name' => $event->name,
                    'description' => $event->description,
                    'location' => $event->location,
                    'time_frame_text' => [
                        'start' => $event->start_datetime->toDayDateTimeString(),
                        'end' => $event->end_datetime->toDayDateTimeString(),
                    ],
                    "time_frame_short" => [
                        'start' => $event->start_datetime->format('M jS, Y, g:ia'),
                        'end' => $event->end_datetime->format('M jS, Y, g:ia'),
                    ],
                    'time_frame' => [
                        'start' => $event->start_datetime->format('Y-m-d H:i:s'),
                        'end' => $event->end_datetime->format('Y-m-d H:i:s'),
                    ],
                    "owner_id" => $event->owner_id,
                ],
                [
                    'name' => $event2->name,
                    'description' => $event2->description,
                    'location' => $event2->location,
                    "owner_id" => $event2->owner_id,
                ],
                [
                    'name' => $event3->name,
                    'description' => $event3->description,
                    'location' => $event3->location,
                    "owner_id" => $event3->owner_id,
                ],
            ]
        ]);

    }

    public function test_user_can_search_their_events()
    {

        $user = User::factory()->create();

        //Invited and Owned Event
        $event = Event::factory()->hasAttached($user)->create([
            "owner_id" => $user->id,
            "name" => "Beach Trip"
        ]);

        $event2 = Event::factory()->hasAttached($user)->create([
            "owner_id" => $user->id,
            "name" => "Cabin Trip"
        ]);

        Event::factory()->create([
            "name" => "Other Beach Trip"
        ]);

        Event::factory()->create([
            "name" => "Other Cabin Trip"
        ]);        

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event?search=Beach');

        $response->assertStatus(200); 

        $this->assertCount(1,$response['data']);
        $response->assertJson([
            "data" => [
                [
                    'name' => $event->name,
                    'description' => $event->description,
                    'location' => $event->location,
                    "owner_id" => $event->owner_id,
                ],
            ]
        ]);

    }

    public function test_user_without_any_events()
    {

        $user = User::factory()->create();

        Event::factory()->create([
            "name" => "Other Beach Trip"
        ]);

        Event::factory()->create([
            "name" => "Other Cabin Trip"
        ]);        

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event');

        $response->assertStatus(200); 

        $this->assertCount(0,$response['data']);
        $response->assertJson([
            "data" => [
            ]
        ]);
    }

}
