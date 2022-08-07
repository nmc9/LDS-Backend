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

class CanUpdateBringableDetailsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_update_a_bringables_details()
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


        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('PUT','/api/bringable/' . $b1->id,[
                'name' => "Towel",
                'notes' => "This is a towel",
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $b1->id,
                'name' => "Towel",
                'notes' => "This is a towel",
                'importance' => $b1->importance,
                'required_count' => 7,
                'acquired_count' => 7,
                'acquired_all' => true,
                'items' => [
                    [
                        'id' => $bi1->id,
                        'required' => $bi1->required,
                        'acquired' => $bi1->acquired,  
                    ]
                ]
                
            ]
        ]);

        $this->assertDatabaseHas('bringables',[
            'id' => $b1->id,
            'name' => "Towel",
            'notes' => "This is a towel",
        ]);


    }



}
