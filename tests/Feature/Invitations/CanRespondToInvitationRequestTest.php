<?php

namespace Tests\Feature\Invitations;

use App\Library\Constants;
use App\Mail\NotificationInvitationAccepted;
use App\Mail\NotificationInvitationDeclined;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CanRespondToInvitationRequestTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_accept_to_a_request()
    {

        \Mail::fake();
        $invitation = Invitation::factory()->create([
            "inviter_id" => User::factory()->create()->id,
        ]);

        $response = $this->get('/invitation-response?token=' . $invitation->token . '&response=Accept');

        $response->assertStatus(302);

        $this->assertDatabaseHas('invitations',[
            'id' => $invitation->id,
            'status' => Constants::INVITATION_ACCEPTED,
        ]);

        \Mail::assertSent(NotificationInvitationAccepted::class);

    }

    public function test_user_can_decline_to_a_request(){

        \Mail::fake();
        $invitation = Invitation::factory()->create([
            "inviter_id" => User::factory()->create()->id,
        ]);

        $response = $this->get('/invitation-response?token=' . $invitation->token . '&response=Decline');

        $response->assertStatus(302);

        $this->assertDatabaseHas('invitations',[
            'id' => $invitation->id,
            'status' => Constants::INVITATION_DECLINED,

        ]);

        \Mail::assertSent(NotificationInvitationDeclined::class);

    }

}
