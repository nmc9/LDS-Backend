<?php

namespace Tests\Feature\Profiles;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanViewProfilePageTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_view_profile()
    {
        $user = User::factory()->create();


        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('GET','/api/profile');

        $response->assertStatus(200);

       $this->assertEquals($user->id,$response['data']['id']);

    }


    public function test_cant_view_profile_if_not_auth()
    {
        $user = User::factory()->create();

        $response = $this->json('GET','/api/profile');

        $response->assertStatus(401);

        $response->assertJson([
            "message" => "Unauthenticated."
        ]);

    }

}
