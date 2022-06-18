<?php

namespace Tests\Feature\Profiles;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class CanUserAuthenticateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_authenticate()
    {

        $user = User::factory()->create([
            'email' => "test@example.com",
            'password' => \Hash::make('password'),
        ]);

        // \Mail::fake();

        $response = $this->json('POST','/api/login',[
            'email' => 'test@example.com',
            'password' => 'password',
            'device_name' => 'Nick\'s Phone'
        ]);

        // $response->dump();

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "user" => [
                "id",
                "name",
                "email",
                "updated_at",
                "created_at",
            ],
            "token"
        ]);
    }


    public function test_user_can_logout()
    {

        $user = User::factory()->create([
            'email' => "test@example.com",
            'password' => \Hash::make('password'),
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/logout',[
            'email' => 'test@example.com',
            'password' => 'password',
            'device_name' => 'Nick\'s Phone'
        ]);


        $response->assertStatus(200);

    }


}
