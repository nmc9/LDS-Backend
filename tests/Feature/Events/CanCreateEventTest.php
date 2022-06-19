<?php

namespace Tests\Feature\Events;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class CanCreateEventTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_an_event()
    {

        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/event',[
            'name' => 'Go To Vegas',
            'description' => 'My family Wants to go to Vegas',
            'location' => 'Vegas',
            'start_datetime' => '2022-12-25 00:00:00',
            "end_datetime" => "2022-12-28 23:59:59",
        ]);

        $response->assertStatus(201);

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
            'name' => 'Go To Vegas',
            'description' => 'My family Wants to go to Vegas',
            'location' => 'Vegas',
            'start_datetime' => '2022-12-25 00:00:00',
            "end_datetime" => "2022-12-28 23:59:59",
        ]); 

    }

    public function test_cant_create_event_with_bad_datetime_format()
    {

        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/event',[
            'name' => 'Go To Vegas',
            'description' => 'My family Wants to go to Vegas',
            'location' => 'Vegas',
            'start_datetime' => '12/15',
            "end_datetime" => "12/15",
        ]);

        // $response->dump();

        $response->assertStatus(422);

        $response->assertJson([
            "errors" => [
            ]
        ]);

    }


}
