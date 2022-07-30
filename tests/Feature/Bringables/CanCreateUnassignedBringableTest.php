<?php

namespace Tests\Feature\Events;

use App\Models\Event;
use App\Models\Friend;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanCreateUnassignedBringableTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_an_unassigned_bringable()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);



        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/event/' . $event->id . '/bringable/',[
            'name' => 'Socks',
            'notes' => 'I need socks',
            'importance' => 1,
            'assigned_id' => null,
            'required' => 1,
            'acquired' => 1,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('bringables',[
            'event_id' => $event->id,
            'name' => 'Socks',
            'notes' => 'I need socks',
            'importance' => 1,
        ]); 

        $this->assertDatabaseHas('bringable_items',[
            'assigned_id' => null,
            'required' => 1,
            'acquired' => 1,
        ]); 

    }


    public function test_can_create_some_unassigned_bringables_multiple()
    {

        $user = User::factory()->create();
        $user_2 = User::factory()->create();

        Friend::factory()->create([
            'from_user_id' => $user->id,
            'to_user_id' => $user_2->id
        ]);

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);



        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/event/' . $event->id . '/bringable/',[
            'name' => 'Socks',
            'notes' => 'I need socks',
            'importance' => 1,
            'assigned_id' => null,
            'required' => 30,
            'acquired' => 30,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('bringables',[
            'event_id' => $event->id,
            'name' => 'Socks',
            'notes' => 'I need socks',
            'importance' => 1,
        ]); 

        $this->assertDatabaseHas('bringable_items',[
            'assigned_id' => null,
            'required' => 30,
            'acquired' => 30,
        ]); 

    }


    public function test_can_create_some_unassigned_bringables_infinity()
    {

        $user = User::factory()->create();
        $user_2 = User::factory()->create();

        Friend::factory()->create([
            'from_user_id' => $user->id,
            'to_user_id' => $user_2->id
        ]);

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);



        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/event/' . $event->id . '/bringable/',[
            'name' => 'Socks',
            'notes' => 'I need socks',
            'importance' => 1,
            'assigned_id' => null,
            'required' => -1,
            'acquired' => -1,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('bringables',[
            'event_id' => $event->id,
            'name' => 'Socks',
            'notes' => 'I need socks',
            'importance' => 1,
        ]); 

        $this->assertDatabaseHas('bringable_items',[
            'assigned_id' => null,
            'required' => -1,
            'acquired' => -1,
        ]); 

    }

    public function test_can_create_a_bringable_for_an_event_they_are_invited_to()
    {

        $user = User::factory()->create();
        $user_2 = User::factory()->create();

        Friend::factory()->create([
            'from_user_id' => $user->id,
            'to_user_id' => $user_2->id
        ]);

        $event = Event::factory()->create([
            // 'owner_id' => $user->id
        ]);

        Invitation::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id
        ]);



        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/event/' . $event->id . '/bringable/',[
            'name' => 'Socks',
            'notes' => 'I need socks',
            'importance' => 1,
            'assigned_id' => null,
            'required' => -1,
            'acquired' => 1,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('bringables',[
            'event_id' => $event->id,
            'name' => 'Socks',
            'notes' => 'I need socks',
            'importance' => 1,
        ]); 

        $this->assertDatabaseHas('bringable_items',[
            'assigned_id' => null,
            'required' => -1,
            'acquired' => 1,
        ]); 

    }

    public function test_cant_create_a_bringable_for_an_event_they_dont_belong_to()
    {

        $user = User::factory()->create();
        $user_2 = User::factory()->create();

        Friend::factory()->create([
            'from_user_id' => $user->id,
            'to_user_id' => $user_2->id
        ]);

        $event = Event::factory()->create([
            // 'owner_id' => $user->id
        ]);




        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/event/' . $event->id . '/bringable/',[
            'name' => 'Socks',
            'notes' => 'I need socks',
            'importance' => 1,
            'assigned_id' => null,
            'required' => -1,
            'acquired' => 1,
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('bringables',[
            'event_id' => $event->id,
            'name' => 'Socks',
            'notes' => 'I need socks',
            'importance' => 1,
        ]); 

        $this->assertDatabaseMissing('bringable_items',[
            'assigned_id' => null,
            'required' => -1,
            'acquired' => 1,
        ]); 

    }


}
