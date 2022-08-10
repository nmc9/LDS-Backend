<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BringableMail extends Mailable
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
        $to_name,
        $event_name,
        $bringable_name)
    {
        $this->event_name = $event_name;
        $this->to_name = $to_name;
        $this->bringable_name = $bringable_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("LDS Bringable for " . $event_name)
        ->markdown('emails.bringable')->with([
            "to_name" => $this->to_name,
            "bringable_name" => $this->bringable_name,
            "event_name" => $this->event_name,
        ]);
    }
}
