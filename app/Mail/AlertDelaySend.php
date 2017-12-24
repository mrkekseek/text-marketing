<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlertDelaySend extends Mailable
{
    use Queueable, SerializesModels;

    public $alerts;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($alerts)
    {
        $this->alerts = $alerts;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.alert_delay')->subject('New Alerts Received');
    }
}
