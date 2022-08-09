<?php

namespace Tests\Feature\Events;

use App\Models\Bringable;
use App\Models\BringableItem;
use App\Models\Event;
use App\Models\Friend;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanCreateBringableItemTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_assigned()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        $b1 = Bringable::factory()->for($event)->create();
        $bi1 = BringableItem::factory()->for($b1)->create([
            'acquired' => 2,
            'required' => 2,
            'assigned_id' => $user->id,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/bringable/' . $b1->id .'/items/',[
            'required' => 3,
            'acquired' => 2,
            'assigned_id' => $user->id,
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                "required" => 3,
                "acquired" => 2,
                'bringable_id' => $b1->id,
                'assigned' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ],                
            ]
        ]);

        $this->assertDatabaseHas('bringable_items',[
            'required' => 3,
            'acquired' => 2,
            'assigned_id' => $user->id
        ]);


    }



    public function test_can_create_unassigned()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        $b1 = Bringable::factory()->for($event)->create();


        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/bringable/' . $b1->id .'/items/',[
            'required' => 3,
            'acquired' => 2,
            'assigned_id' => null,
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                "required" => 3,
                "acquired" => 2,
                'bringable_id' => $b1->id,
                'assigned' => null,                
            ]
        ]);

        $this->assertDatabaseHas('bringable_items',[
            'required' => 3,
            'acquired' => 2,
            'assigned_id' => null
        ]);


    }

    public function test_can_create_an_infinity()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        $b1 = Bringable::factory()->for($event)->create();
        $bi1 = BringableItem::factory()->for($b1)->create([
            'acquired' => -1,
            'required' => -1,
            'assigned_id' => $user->id,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/bringable/' . $b1->id .'/items/',[
            'required' => 3,
            'acquired' => 2,
            'assigned_id' => $user->id,
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                "required" => -1,
                "acquired" => 2,
                'bringable_id' => $b1->id,
                'assigned' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ],                
            ]
        ]);

        $this->assertDatabaseHas('bringable_items',[
            'required' => -1,
            'acquired' => 2,
            'assigned_id' => $user->id
        ]);


    }


}
