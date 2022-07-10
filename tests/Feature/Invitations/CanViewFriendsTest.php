<?php

namespace Tests\Feature\Events;

use App\Models\Event;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanViewFriendsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_view_friend_list_where_they_have_sent()
    {

        $user = User::factory()->create([
            "name" => "Sender"
        ]);

        $otherUser = User::factory()->create([
            "name" => "Receiver"
        ]);

        Friend::factory()->create([
            "from_user_id" => $user->id,
            "to_user_id" => $otherUser->id,
            "accepted" => true,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/friend/');

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

    public function test_can_view_friend_list_where_they_have_received()
    {

        $user = User::factory()->create([
            "name" => "Receiver"
        ]);

        $otherUser = User::factory()->create([
            "name" => "Sender"
        ]);

        Friend::factory()->create([
            "from_user_id" => $otherUser->id,
            "to_user_id" => $user->id,
            "accepted" => true,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/friend/');

        $response->assertStatus(200);

        $response->assertJson([
            "data" => [
                [
                    'id' => $otherUser->id,
                    'name' => "Sender",
                    'email' => $otherUser->email,
                ]
            ],
        ]);

    }

    public function test_when_there_are_both_ways_records_only_one_is_returned()
    {

        $user = User::factory()->create([
            "name" => "Joe"
        ]);

        $otherUser = User::factory()->create([
            "name" => "Josh"
        ]);

        Friend::factory()->create([
            "from_user_id" => $user->id,
            "to_user_id" => $otherUser->id,
            "accepted" => true,
        ]);

        Friend::factory()->create([
            "from_user_id" => $otherUser->id,
            "to_user_id" => $user->id,
            "accepted" => true,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/friend/');

        $response->assertStatus(200);

        $this->assertCount(1,$response['data']);
        $response->assertJson([
            "data" => [
                [
                    'id' => $otherUser->id,
                    'name' => "Josh",
                    'email' => $otherUser->email,
                ]
            ],
        ]);
    }


    public function test_does_not_return_unaccepted_friends(){
        $user = User::factory()->create([
            "name" => "Joe"
        ]);

        $otherUser = User::factory()->create([
            "name" => "Josh"
        ]);

        Friend::factory()->create([
            "from_user_id" => $user->id,
            "to_user_id" => $otherUser->id,
            "accepted" => false,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/friend/');

        $response->assertStatus(200);

        $this->assertCount(0,$response['data']);

        $response->assertJson([
            "data" => [
            ],
        ]);        
    }


    public function test_can_force_to_return_non_accepted()
    {

        $user = User::factory()->create([
            "name" => "Joe"
        ]);

        $otherUser = User::factory()->create([
            "name" => "Josh"
        ]);

        $thirdUser = User::factory()->create([
            "name" => "Sam"
        ]);

        Friend::factory()->create([
            "from_user_id" => $user->id,
            "to_user_id" => $otherUser->id,
            "accepted" => false,
        ]);

        Friend::factory()->create([
            "from_user_id" => $thirdUser->id,
            "to_user_id" => $user->id,
            "accepted" => false,
        ]);


        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/friend?includeUnaccepted=true');

        $response->assertStatus(200);

        $this->assertCount(2,$response['data']);
        $response->assertJson([
            "data" => [
                [ 
                    'id' => $thirdUser->id,
                    'name' => "Sam",
                    'email' => $thirdUser->email,
                ],        
                [
                    'id' => $otherUser->id,
                    'name' => "Josh",
                    'email' => $otherUser->email,
                ],

            ],
        ]);
    }

}
