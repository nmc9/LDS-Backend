<?php

namespace Tests\Feature\Profiles;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class CanSearchProfilesTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_search_for_other_users()
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


        Sanctum::actingAs(
            $user1,
            ['*']
        );

        $response = $this->json('GET','/api/search/profile?search=Nick%20Doe');

        $response->assertStatus(200);

        $this->assertEquals($user2->id,$response['data'][0]['id']);
        $this->assertEquals($user2->name,$response['data'][0]['name']);
        $this->assertEquals($user2->email,$response['data'][0]['email']);

    }

    public function test_user_can_search_for_other_users_no_input()
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


        Sanctum::actingAs(
            $user3,
            ['*']
        );

        $response = $this->json('GET','/api/search/profile');

        $response->assertStatus(200);

        $this->assertCount(3,$response['data']);

    }

}
