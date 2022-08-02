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

class CanSearchUserBringablesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_retrieve_their_bringables_for_an_event()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        $b1 = Bringable::factory()->for($event)->create();
        $bi1 = BringableItem::factory()->for($b1)->create([
            'assigned_id' => $user->id,
            'acquired' => 44,
            'required' => 44,
        ]);
        $bi1x = BringableItem::factory()->for($b1)->create([
            'acquired' => 8,
            'required' => 8,
        ]);
        $b2 = Bringable::factory()->for($event)->create();

        $bi2 = BringableItem::factory()->create([
            'assigned_id' => $user->id,
            'bringable_id' => $b2->id,
            'acquired' => 99,
            'required' => -1,
        ]);

        $b3 = Bringable::factory()->for($event)->create();
        $bi3 = BringableItem::factory()->create([
            'bringable_id' => $b3->id,
            'acquired' => 7,
            'required' => -1,
        ]);


        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/' . $event->id . '/user/' . $user->id . '/bringable');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'id' => $b1->id,
                    'name' => $b1->name,
                    'notes' => $b1->notes,
                    'importance' => $b1->importance,
                    'required_count' => 44,
                    'acquired_count' => 44,
                    'acquired_all' => true,
                    'items' => [
                        [
                            'id' => $bi1->id,
                            'required' => $bi1->required,
                            'acquired' => $bi1->acquired,  
                        ]
                    ]
                ],
                [
                    'name' => $b2->name,
                    'notes' => $b2->notes,
                    'importance' => $b2->importance,
                    'required_count' => -1,
                    'acquired_count' => 99,
                    'acquired_all' => true,
                    'items' => [
                        [
                            'id' => $bi2->id,
                            'required' => $bi2->required,
                            'acquired' => $bi2->acquired,  
                        ]
                    ]
                ],
            ]
        ]);


    }

    public function test_shows_no_bringables_when_that_user_has_none_for_that_event()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        $b1 = Bringable::factory()->create();
        $bi1 = BringableItem::factory()->for($b1)->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/' . $event->id . '/user/' . $user->id . '/bringable');

        $response->assertStatus(200);

        $response->assertJson([
            "data" => []
        ]);


    }


    public function test_can_search_that_users_bringables_for_an_event()
    {
        $user = User::factory()->create();

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        $b1 = Bringable::factory()->for($event)->create([
            'name' => "Socks",
            'notes' => ""

        ]);
        $b2 = Bringable::factory()->for($event)->create([
            'name' => "shoes",
            'notes' => ""
        ]);
        $bi2 = BringableItem::factory()->create([
            'assigned_id' => $user->id,
            'bringable_id' => $b2->id,
            'acquired' => 99,
            'required' => -1,
        ]);


        $b3 = Bringable::factory()->for($event)->create([
            'name' => 'pants',
            'notes' => 'And Shoes'
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->json('GET','/api/event/' . $event->id . '/user/' . $user->id . '/bringable?search=shoes');

        $response->assertStatus(200);
// $response->dd(); 

        $response->assertJson([
            'data' => [
                [
                    'id' => $b2->id,
                    'name' => $b2->name,
                    'notes' => $b2->notes,
                    'importance' => $b2->importance,
                    'required_count' => -1,
                    'acquired_count' => 99,
                    'acquired_all' => true,
                    'items' => [
                        [
                            'id' => $bi2->id,
                            'required' => $bi2->required,
                            'acquired' => $bi2->acquired,  
                        ]
                    ]
                ],
            ]
        ]);

    }


    public function test_can_retrieve_a_users_bringables_for_an_event_only_not_acquired()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        $b1 = Bringable::factory()->for($event)->create();
        $bi1 = BringableItem::factory()->for($b1)->create([
            'assigned_id' => $user->id,
            'acquired' => 7,
            'required' => 7,
        ]);

        $bi1 = BringableItem::factory()->for($b1)->create([
            'acquired' => 77,
            'required' => 77,
        ]);
        $b2 = Bringable::factory()->for($event)->create();

        $bi2 = BringableItem::factory()->create([
            'assigned_id' => $user->id,
            'bringable_id' => $b2->id,
            'acquired' => 7,
            'required' => 14,
        ]);
        $bi2x = BringableItem::factory()->create([
            'bringable_id' => $b2->id,
            'acquired' => 77,
            'required' => 770,
        ]);



        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/' . $event->id . '/user/' . $user->id . '/bringable?acquired=0');

        $response->assertStatus(200);


        $this->assertCount(1,$response['data']);
        $response->assertJson([
            'data' => [
                [
                    'id' => $b2->id,
                    'name' => $b2->name,
                    'notes' => $b2->notes,
                    'importance' => $b2->importance,
                    'required_count' => 14,
                    'acquired_count' => 7,
                    'acquired_all' => false,
                    'items' => [
                        [
                            'id' => $bi2->id,
                            'required' => $bi2->required,
                            'acquired' => $bi2->acquired,  
                        ]
                    ]
                ],
            ]
        ]);


    }


}
