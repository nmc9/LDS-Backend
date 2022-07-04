<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ImaginaryFriendRequestMail extends Mailable
{
    use Queueable, SerializesModels;


    public $from_name;
    public $to_email;
    public $accept_url;
    public $decline_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        $from_name,
        $to_email,
        $accept_url,
        $decline_url)
    {
        $this->from_name = $from_name;
        $this->to_email = $to_email;
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
        return $this->subject("Inviation to LDS")
        ->markdown('emails.ifriendrequest')->with([
            "from_name" => $this->from_name,
            "to_email" => $this->to_email,
            "accept_url" => $this->accept_url,
            "decline_url" => $this->decline_url,
        ]);
    }
}
