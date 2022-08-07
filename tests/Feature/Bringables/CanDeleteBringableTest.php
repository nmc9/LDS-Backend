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

class CanDeleteBringableTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_delete_a_bringable()
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

        $response = $this->json('DELETE','/api/bringable/' . $b1->id);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Success',                
        ]);

        $this->assertDatabaseMissing('bringables',[
            'id' => $b1->id,
        ]);


    }



}
