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

class CanSearchGroupBringablesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_retrieve_group_bringables_for_an_event()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        $b1 = Bringable::factory()->for($event)->create();
        $bi1 = BringableItem::factory()->for($b1)->create([
            'acquired' => 7,
            'required' => 7,
        ]);
        $b2 = Bringable::factory()->for($event)->create();

        $bi2 = BringableItem::factory()->create([
            'bringable_id' => $b2->id,
            'acquired' => 7,
            'required' => -1,
        ]);


        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/' . $event->id . '/bringable/');

        $response->assertStatus(200);


        $response->assertJson([
            'data' => [
                [
                    'id' => $b1->id,
                    'name' => $b1->name,
                    'notes' => $b1->notes,
                    'importance' => $b1->importance,
                    'required_count' => 7,
                    'acquired_acount' => 7,
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
                    'acquired_acount' => 7,
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

    public function test_shows_no_bringables_when_none_exist_for_event()
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

        $response = $this->json('GET','/api/event/' . $event->id . '/bringable/');

        $response->assertStatus(200);

        $response->assertJson([
            "data" => []
        ]);


    }


    public function test_can_search_group_bringables_for_an_event()
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
        $b3 = Bringable::factory()->for($event)->create([
            'name' => 'pants',
            'notes' => 'And Shoes'
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/' . $event->id . '/bringable?search=shoes');

        $response->assertStatus(200);


        $response->assertJson([
            'data' => [
                [
                    'id' => $b2->id,
                    'name' => $b2->name,
                    'notes' => $b2->notes,
                    'importance' => $b2->importance,
                    'required_count' => 0,
                    'acquired_acount' => 0,
                    'acquired_all' => false,
                    'items' => [
                    ]
                ],
                [
                    'id' => $b3->id,
                    'name' => $b3->name,
                    'notes' => $b3->notes,
                    'importance' => $b3->importance,
                    'required_count' => 0,
                    'acquired_acount' => 0,
                    'acquired_all' => false,
                    'items' => [
                    ]
                ],
            ]
        ]);

    }


    public function test_can_retrieve_group_bringables_for_an_event_only_acquired()
    {

        $user = User::factory()->create();

        $event = Event::factory()->create([
            'owner_id' => $user->id
        ]);

        $b1 = Bringable::factory()->for($event)->create();
        $bi1 = BringableItem::factory()->for($b1)->create([
            'acquired' => 7,
            'required' => 7,
        ]);
        $b2 = Bringable::factory()->for($event)->create();

        $bi2 = BringableItem::factory()->create([
            'bringable_id' => $b2->id,
            'acquired' => 7,
            'required' => 70,
        ]);


        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/event/' . $event->id . '/bringable?acquired=1');

        $response->assertStatus(200);

        $this->assertCount(1,$response['data']);
        $response->assertJson([
            'data' => [
                [
                    'id' => $b1->id,
                    'name' => $b1->name,
                    'notes' => $b1->notes,
                    'importance' => $b1->importance,
                    'required_count' => 7,
                    'acquired_acount' => 7,
                    'acquired_all' => true,
                    'items' => [
                        [
                            'id' => $bi1->id,
                            'required' => $bi1->required,
                            'acquired' => $bi1->acquired,  
                        ]
                    ]
                ],
            ]
        ]);


    }


}
