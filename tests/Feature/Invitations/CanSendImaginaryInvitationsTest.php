<?php

namespace Tests\Feature\Events;

use App\Library\Constants;
use App\Library\Invitations\InvitationService;
use App\Mail\InvitationImaginaryRequestMail;
use App\Mail\InvitationReminderMail;
use App\Mail\InvitationRequestMail;
use App\Models\Event;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanSendImaginaryInvitationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_send_imaginary_invitation_to_an_array_of_users()
    {

        \Mail::fake();

        $user = User::factory()->create([
            "name" => "Sender",
            "id" => 999,
        ]);

        $user2 = User::factory()->create([ 'id' => 986 ]);
        $user3 = User::factory()->create([ 'id' => 685 ]);

        $event = Event::factory()->create();

        Friend::factory()->create([
            "from_user_id" => $user->id,
            "to_user_id" => $user2->id,
            "accepted" => true,
        ]);

        Friend::factory()->create([
            "from_user_id" => $user3->id,
            "to_user_id" => $user->id,
            "accepted" => true,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/event/'. $event->id . '/invitation/',[
            'emails' => ['test@example.com','test2@example.com'],
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            "message" => "Success",
        ]);
        \Mail::assertSent(InvitationImaginaryRequestMail::class, function ($mail) use ($user2) {
            return $mail->hasTo('test@example.com');
        });

        \Mail::assertSent(InvitationImaginaryRequestMail::class, function ($mail) use ($user3) {
            return $mail->hasTo('test2@example.com');
        });


    }

    public function test_can_send_imaginary_invitation_to_an_array_of_emails_with_users()
    {

        \Mail::fake();

        $user = User::factory()->create([
            "name" => "Sender",
            "id" => 999,
        ]);

        $user2 = User::factory()->create([ 'id' => 986 ]);
        $user3 = User::factory()->create([ 'id' => 685 ]);

        $event = Event::factory()->create();

        Friend::factory()->create([
            "from_user_id" => $user->id,
            "to_user_id" => $user2->id,
            "accepted" => true,
        ]);

        Friend::factory()->create([
            "from_user_id" => $user3->id,
            "to_user_id" => $user->id,
            "accepted" => true,
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/event/'. $event->id . '/invitation/',[
            'users' => [$user2->id,$user3->id],
            'emails' => ['test@example.com','test2@example.com']
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            "message" => "Success",
        ]);

        $this->assertDatabaseHas('invitations',[
            'user_id' => $user2->id,
            'event_id' => $event->id,
            'inviter_id' => $user->id,
            'status' => Constants::INVITATION_PENDING,
        ]);

        $this->assertDatabaseHas('invitations',[
            'user_id' => $user3->id,
            'event_id' => $event->id,
            'inviter_id' => $user->id,
            'status' => Constants::INVITATION_PENDING,
        ]);

        \Mail::assertSent(InvitationRequestMail::class, function ($mail) use ($user2) {
            return $mail->hasTo($user2->email);
        });

        \Mail::assertSent(InvitationRequestMail::class, function ($mail) use ($user3) {
            return $mail->hasTo($user3->email);
        });

        \Mail::assertSent(InvitationImaginaryRequestMail::class, function ($mail) use ($user2) {
            return $mail->hasTo('test@example.com');
        });

        \Mail::assertSent(InvitationImaginaryRequestMail::class, function ($mail) use ($user3) {
            return $mail->hasTo('test2@example.com');
        });
    }

}
