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
        $user = User::factory()->create([
            'email' => 'nick@example.com',
            'name' => 'John Doe',
        ]);
        $user = User::factory()->create([
            'email' => 'jake@example.com',
            'name' => 'Nick Doe',
        ]);
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'name' => 'Josh Doe',
        ]);


        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/search/profile/'. 'nick');

        $response->assertStatus(200);

        $response->dump();

        $this->assertEquals($user->id,$response['data']['id']);
        $this->assertEquals($user->name,$response['data']['name']);
        $this->assertEquals($user->email,$response['data']['email']);

    }

}
