<?php

namespace Tests\Feature\Profiles;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanDeleteFriendTest extends TestCase
{

    use RefreshDatabase;



    public function test_user_can_delete()
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

        $response = $this->json('DELETE','/api/friend/' . $user2->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('friends',[
            'from_user_id' => $user1,
            'to_user_id' => $user2,
        ]);

    }


}
