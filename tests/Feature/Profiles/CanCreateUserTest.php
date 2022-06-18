<?php

namespace Tests\Feature\Profiles;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CanCreateUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_a_profile()
    {

        // \Mail::fake();

        $response = $this->json('POST','/api/register',[
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'device_name' => 'Nick\'s Phone'
        ]);

        // $response->dump();

        $response->assertStatus(201);

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

        $this->assertDatabaseHas('users',[
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]); 
    }


    public function test_can_create_a_profile_with_availablity()
    {
        $response = $this->json('POST','/api/register',[
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'device_name' => 'Nick\'s Phone',
            'availabilities' => [
                "sunday" => [
                    "start" => 
                    [
                        "hours" =>  10,
                        "minutes" =>  0
                    ],
                    "end" => []
                ],
                "monday" => [
                    "start" => 
                    [
                        "hours" =>  10,
                        "minutes" =>  0
                    ],
                    "end" => [
                        "hours" =>  20,
                        "minutes" =>  0
                    ],
                ],
                "tuesday" => [
                    "start" => [],
                    "end" => [
                        "hours" =>  20,
                        "minutes" =>  0
                    ],
                ],
                "wednesday" => [
                    "start" => [],
                    "end" => [],
                ],
                "thursday" => [
                    "start" => [],
                    "end" => [],
                ],
                "friday" => [
                    "start" => [],
                    "end" => [],
                ],
                "saturday" => [
                    "start" => [],
                    "end" => [],
                ],
            ]
        ]);

        $response->assertStatus(201);

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

        $this->assertDatabaseHas('users',[
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]); 

        $this->assertDatabaseHas('availabilities',[
            'day_of_week' => 'monday',
            'start_time' => '10:00:00',
            'end_time' => '20:00:00',
        ]);

        $this->assertDatabaseHas('availabilities',[
            'day_of_week' => 'sunday',
            'start_time' => '10:00:00',
            'end_time' => '24:00:00',
        ]);

        $this->assertDatabaseHas('availabilities',[
            'day_of_week' => 'tuesday',
            'start_time' => '00:00:00',
            'end_time' => '20:00:00',
        ]);
    }


    public function test_cant_create_a_profile_when_account_exists()
    {

        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->json('POST','/api/register',[
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'device_name' => 'Nick\'s Phone'
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            "message" => "The email has already been taken."
        ]);
    }

}
