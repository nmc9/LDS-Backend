<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationImaginaryFriendRequestMail extends Mailable
{
    use Queueable, SerializesModels;


    public $to_email;
    public $from_name;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        $to_email,
        $from_name)
    {
        $this->to_email = $to_email;
        $this->from_name = $from_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Imaginary Friend Request Notification")
        ->markdown('emails.notifications.ifriendrequest')->with([
            "to_email" => $this->to_email,
            "from_name" => $this->from_name,
        ]);
    }
}
