<?php

namespace Tests\Feature\Profiles;

use App\Mail\FriendRequestMail;
use App\Mail\ImaginaryFriendRequestMail;
use App\Mail\NotificationFriendRequestMail;
use App\Mail\NotificationImaginaryFriendRequestMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanSendFriendRequestTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_send_request_to_other_user()
    {

        \Mail::fake();

        $user1 = User::factory()->create([
            'email' => 'nick@example.com',
            'name' => 'John Doe',
        ]);
        $user2 = User::factory()->create([
            'email' => 'jake@example.com',
            'name' => 'Nick Doe',
        ]);

        Sanctum::actingAs(
            $user1,
            ['*']
        );

        $response = $this->json('POST','/api/friend', ['user_id' => $user2->id]);



        $response->assertStatus(200);

        $response->assertJson([
            'msg' => 'Success'
        ]);

        $this->assertDatabaseHas('friends',[
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'accepted' => false,
        ]);


        \Mail::assertSent(FriendRequestMail::class, function ($mail) use ($user2) {
            return $mail->hasTo($user2->email);
        });

        \Mail::assertSent(NotificationFriendRequestMail::class, function ($mail) use ($user1) {
            return $mail->hasTo($user1->email);
        });

    }

    public function test_user_can_send_request_to_imaginary_friend()
    {

        \Mail::fake();

        $user1 = User::factory()->create([
            'email' => 'nick@example.com',
            'name' => 'John Doe',
        ]);

        Sanctum::actingAs(
            $user1,
            ['*']
        );

        $response = $this->json('POST','/api/imaginary/friend', ['email' => "test@example.com"]);

        $response->assertStatus(200);

        $response->assertJson([
            'msg' => 'Success'
        ]);

        $this->assertDatabaseHas('imaginary_friends',[
            'from_user_id' => $user1->id,
            'to_user_email' => "test@example.com",
            'accepted' => false,
        ]);


        \Mail::assertSent(ImaginaryFriendRequestMail::class, function ($mail){
            return $mail->hasTo("test@example.com");
        });

        \Mail::assertSent(NotificationImaginaryFriendRequestMail::class, function ($mail) use ($user1) {
            return $mail->hasTo($user1->email);
        });

    }

}
