<?php

namespace Tests\Feature\Profiles;

use App\Models\Friend;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CanRespondToFriendRequestTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_accept_to_a_request()
    {
        $friend = Friend::factory()->create();

        $response = $this->get('/friend-response?token=' . $friend->token . '&response=Accept');


        $this->assertDatabaseHas('friends',[
            'id' => $friend->id,
            'accepted' => true,
        ]);
        $response->assertStatus(302);

    }

    public function test_user_can_decline_to_a_request(){
        $friend = Friend::factory()->create();

        $response = $this->get('/friend-response?token=' . $friend->token . '&response=Decline');

        $response->assertStatus(302);

        $this->assertDatabaseMissing('friends',[
            'id' => $friend->id,
        ]);
    }

}
