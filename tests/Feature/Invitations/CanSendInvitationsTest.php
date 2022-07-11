<?php

namespace Tests\Feature\Events;

use App\Library\Constants;
use App\Library\Invitations\InvitationService;
use App\Mail\InvitationReminderMail;
use App\Mail\InvitationRequestMail;
use App\Models\Event;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CanSendInvitationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_send_invitation_to_an_array_of_users()
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

    }

    public function test_cant_send_invitation_to_someone_they_are_not_friends_with()
    {
        \Mail::fake();

        $user = User::factory()->create([
            "name" => "Sender"
        ]);

        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $event = Event::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->json('POST','/api/event/'. $event->id . '/invitation/',[
            'users' => [$user2->id,$user3->id],
        ]);

        $response->assertStatus(200);

        $this->assertContains([
            $user2->id,
            InvitationService::NOT_FRIENDS,
        ] ,$response['results']);
        $this->assertContains([
            $user3->id,
            InvitationService::NOT_FRIENDS,
        ] ,$response['results']);


        $this->assertDatabaseMissing('invitations',[
            'user_id' => $user2->id,
            'event_id' => $event->id,
        ]);

        $this->assertDatabaseMissing('invitations',[
            'user_id' => $user3->id,
            'event_id' => $event->id,
        ]);

        \Mail::assertNotSent(InvitationRequestMail::class);

        \Mail::assertNotSent(InvitationReminderMail::class);
    }

}
