<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationInvitationAccepted extends Mailable
{
    use Queueable, SerializesModels;


    public $invited_user_name;
    public $event_name;
    public $inviter_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        $invited_user_name,
        $event_name,
        $inviter_name)
    {
        $this->invited_user_name = $invited_user_name;
        $this->event_name = $event_name;
        $this->inviter_name = $inviter_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->invited_user_name . " accepted the invitation to " . $this->event_name)
        ->markdown('emails.notifications.accept-invitation')->with([
            "invited_user_name" => $this->invited_user_name,
            "event_name" => $this->event_name,
            "inviter_name" => $this->inviter_name,
        ]);
    }
}
