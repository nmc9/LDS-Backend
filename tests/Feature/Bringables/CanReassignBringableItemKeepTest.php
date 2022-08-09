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

class CanReassignBringableItemKeepTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_reassign_keeping_acquired()
    {

        $user = User::factory()->create();
        $user2 = User::factory()->create();


        Friend::factory()
        ->accepted()
        ->create([
            'to_user_id' => $user->id,
            'from_user_id' => $user2->id
        ]);

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        Invitation::factory()
        ->for($event)
        ->accepted()
        ->create([
            'user_id' => $user2->id
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

        $response = $this->json('POST','/api/bringableitem/reassign/' . $bi1->id,[
            'assigned_id' => $user2->id ,
            'keep' => 1,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $bi1->id,
                'acquired' => 2,
                'required' => 2,
                'bringable_id' => $b1->id,
                'assigned' => [
                    'id' => $user2->id,
                    'name' => $user2->name,
                    'email' => $user2->email
                ],                
            ]
        ]);

        $this->assertDatabaseHas('bringable_items',[
            'id' => $bi1->id,
            'acquired' => 2,
            'required' => 2,
            'assigned_id' => $user2->id
        ]);


    }



    public function test_can_unassign_keeping_acquired()
    {

        $user = User::factory()->create();
        $user2 = User::factory()->create();


        Friend::factory()
        ->accepted()
        ->create([
            'to_user_id' => $user->id,
            'from_user_id' => $user2->id
        ]);

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        Invitation::factory()
        ->for($event)
        ->accepted()
        ->create([
            'user_id' => $user2->id
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

        $response = $this->json('POST','/api/bringableitem/reassign/' . $bi1->id,[
            'assigned_id' => null,
            'keep' => 1,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $bi1->id,
                'acquired' => 2,
                'required' => 2,
                'bringable_id' => $b1->id,
                'assigned' => null,                
            ]
        ]);

        $this->assertDatabaseHas('bringable_items',[
            'id' => $bi1->id,
            'acquired' => 2,
            'required' => 2,
            'assigned_id' => null
        ]);


    }


    public function test_can_assign_from_unassigned_keeping_acquired()
    {

        $user = User::factory()->create();
        $user2 = User::factory()->create();


        Friend::factory()
        ->accepted()
        ->create([
            'to_user_id' => $user->id,
            'from_user_id' => $user2->id
        ]);

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        Invitation::factory()
        ->for($event)
        ->accepted()
        ->create([
            'user_id' => $user2->id
        ]);

        $b1 = Bringable::factory()->for($event)->create();
        $bi1 = BringableItem::factory()->for($b1)->create([
            'acquired' => 2,
            'required' => 2,
            'assigned_id' => null,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/bringableitem/reassign/' . $bi1->id,[
            'assigned_id' => $user2->id ,
            'keep' => 1,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $bi1->id,
                'acquired' => 2,
                'required' => 2,
                'bringable_id' => $b1->id,
                'assigned' => [
                    'id' => $user2->id,
                    'name' => $user2->name,
                    'email' => $user2->email
                ],                
            ]
        ]);

        $this->assertDatabaseHas('bringable_items',[
            'id' => $bi1->id,
            'acquired' => 2,
            'required' => 2,
            'assigned_id' => $user2->id
        ]);


    }


}
