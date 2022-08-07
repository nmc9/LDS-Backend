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

class CanUpdateBringableItemRequiredTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_increase_required()
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

        $response = $this->json('PUT','/api/bringableitem/' . $bi1->id,[
            'required' => 3,
            'acquired' => 2,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $bi1->id,
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
            'id' => $bi1->id,
            "required" => 3,
            "acquired" => 2,
        ]);


    }



    public function test_can_decrease_required()
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

        $response = $this->json('PUT','/api/bringableitem/' . $bi1->id,[
            'required' => 1,
            'acquired' => 2,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $bi1->id,
                "required" => 1,
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
            'id' => $bi1->id,
            "required" => 1,
            "acquired" => 2,
        ]);


    }

    public function test_can_only_set_infinity_to_negative_1()
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

        $response = $this->json('PUT','/api/bringableitem/' . $bi1->id,[
            'required' => 20,
            'acquired' => 2,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $bi1->id,
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
            'id' => $bi1->id,
            "required" => -1,
            "acquired" => 2,
        ]);


    }


}
