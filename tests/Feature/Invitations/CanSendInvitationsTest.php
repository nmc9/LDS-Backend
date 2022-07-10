<?php

namespace Tests\Feature\Events;

use App\Models\Event;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanSendInvitationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_send_invitation_to_an_array_of_users()
    {

        $user = User::factory()->create([
            "name" => "Sender"
        ]);

        $event = Event::factory()->create();

        Friend::factory()->create([
            "from_user_id" => $user->id,
            "to_user_id" => $otherUser->id,
            "accepted" => true,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/'. $event->id . '/invitation/',[
            
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            "data" => [
                [
                    'id' => $otherUser->id,
                    'name' => "Receiver",
                    'email' => $otherUser->email,
                ]
            ],
        ]);

    }

}
