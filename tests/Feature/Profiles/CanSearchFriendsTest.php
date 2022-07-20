<?php

namespace Tests\Feature\Profiles;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanSearchFriendsTest extends TestCase
{

    use RefreshDatabase;



    public function test_user_can_search_all_for_their_friends()
    {
        $user1 = User::factory()->create([
            'email' => 'nick@example.com',
            'name' => 'John Doe',
        ]);
        $user2 = User::factory()->create([
            'email' => 'jake@example.com',
            'name' => 'Nick Doe',
        ]);
        $user3 = User::factory()->create([
            'email' => 'john@example.com',
            'name' => 'Josh Doe',
        ]);

        Friend::factory()->accepted()->create([
            'from_user_id' => $user1,
            'to_user_id' => $user2,
        ]);

        Friend::factory()->accepted()->create([
            'from_user_id' => $user1,
            'to_user_id' => $user3,
        ]);


        Sanctum::actingAs(
            $user1,
            ['*']
        );

        $response = $this->json('GET','/api/search/friend');

        $response->assertStatus(200);

        $this->assertCount(2,$response['data']);
        $this->assertEquals($user2->id,$response['data'][0]['id']);
        $this->assertEquals($user2->name,$response['data'][0]['name']);
        $this->assertEquals($user2->email,$response['data'][0]['email']);

    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_search_for_their_friends()
    {
        $user1 = User::factory()->create([
            'email' => 'nick@example.com',
            'name' => 'John Doe',
        ]);
        $user2 = User::factory()->create([
            'email' => 'jake@example.com',
            'name' => 'Nick Doe',
        ]);
        $user3 = User::factory()->create([
            'email' => 'john@example.com',
            'name' => 'Josh Doe',
        ]);

        Friend::factory()->accepted()->create([
            'from_user_id' => $user1,
            'to_user_id' => $user2,
        ]);

        Friend::factory()->accepted()->create([
            'from_user_id' => $user1,
            'to_user_id' => $user3,
        ]);


        Sanctum::actingAs(
            $user1,
            ['*']
        );

        $response = $this->json('GET','/api/search/friend?search=Nick%20Doe');

        $response->assertStatus(200);

        $this->assertEquals($user2->id,$response['data'][0]['id']);
        $this->assertEquals($user2->name,$response['data'][0]['name']);
        $this->assertEquals($user2->email,$response['data'][0]['email']);

    }

}
