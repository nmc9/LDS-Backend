<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationImaginaryRequestMail extends Mailable
{
    use Queueable, SerializesModels;


    public $from_name;

    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        $from_name,
        $event)
    {
        $this->from_name = $from_name;
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("LDS Invitation to " . $this->event->name)
        ->markdown('emails.i-invitation')->with([
            "from_name" => $this->from_name,
            "event" => $this->event,
            'app_url' => config('app.url')
        ]);
    }
}
