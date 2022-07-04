<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationFriendRequestMail extends Mailable
{
    use Queueable, SerializesModels;


    public $to_name;
    public $from_name;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        $to_name,
        $from_name)
    {
        $this->to_name = $to_name;
        $this->from_name = $from_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Friend Request To {$this->to_name}")
        ->markdown('emails.notifications.friendrequest')->with([
            "to_name" => $this->to_name,
            "from_name" => $this->from_name,
        ]);
    }
}
