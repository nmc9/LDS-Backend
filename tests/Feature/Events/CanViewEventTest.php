<?php

namespace Tests\Feature\Events;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanViewEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_view_an_event()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/' . $event->id);

        $response->assertStatus(200);

        $response->assertJson([
            "data" => [
                'name' => $event->name,
                'description' => $event->description,
                'location' => $event->location,
                'start_datetime' => $event->start_datetime,
                "end_datetime" => $event->end_datetime,
                "owner_id" => $event->owner_id,
            ],
        ]);

    }

    public function test_cant_view_a_nonexistant_event()
    {

        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/99');

        $response->assertStatus(404);

    }

}
