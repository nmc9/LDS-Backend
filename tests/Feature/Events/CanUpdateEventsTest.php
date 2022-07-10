<?php

namespace Tests\Feature\Events;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanUpdateEventsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_update_an_event()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
            "owner_id" => $user->id,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('PUT','/api/event/' . $event->id,[
            // 'name' => 'Go To Vegas',
            'description' => 'My family Wants to go to Vegas',
            'location' => 'Vegas',
            'start_datetime' => '2022-12-25 00:00:00',
            "end_datetime" => "2022-12-28 23:59:59",
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                'id',
                'name',
                'description',
                'location',
                'start_datetime',
                "end_datetime",
                "owner_id",
            ],
        ]);

        $this->assertDatabaseHas('events',[
            'id' => $event->id,
            'name' => $event->name,
            'description' => 'My family Wants to go to Vegas',
            'location' => 'Vegas',
            'start_datetime' => '2022-12-25 00:00:00',
            "end_datetime" => "2022-12-28 23:59:59",
        ]); 

    }

    public function test_cant_update_event_with_bad_datetime_format()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
            "owner_id" => $user->id,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('PUT','/api/event/' . $event->id,[
            'description' => 'My family Wants to go to Vegas',
            'location' => 'Vegas',
            'start_datetime' => '12/15',
            "end_datetime" => "12/15",
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            "errors" => [
            ]
        ]);

    }

    public function test_cant_update_event_the_user_doesnt_own()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('PUT','/api/event/' . $event->id,[
            'description' => 'My family Wants to go to Vegas',
            'location' => 'Vegas',
            'start_datetime' => '2022-12-25 00:00:00',
            "end_datetime" => "2022-12-28 23:59:59",
        ]);

        $response->assertStatus(403);

        $response->assertJson([
            "message" => "This action is unauthorized."
        ]);

    }

}
