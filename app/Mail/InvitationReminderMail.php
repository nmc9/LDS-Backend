<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $from_name;
    public $to_name;
    public $accept_url;
    public $decline_url;

    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        $from_name,
        $to_name,
        $event,
        $accept_url,
        $decline_url)
    {
        $this->from_name = $from_name;
        $this->to_name = $to_name;
        $this->event = $event;
        $this->accept_url = $accept_url;
        $this->decline_url = $decline_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("LDS Reminder for " . $this->event->name)
        ->markdown('emails.invitation-reminder')->with([
            "from_name" => $this->from_name,
            "to_name" => $this->to_name,
            "accept_url" => $this->accept_url,
            "decline_url" => $this->decline_url,
            "event" => $this->event,
        ]);
    }
}
