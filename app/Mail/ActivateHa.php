<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivateHa extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ha;
    public $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $ha, $link)
    {
        $this->user = $user;
        $this->ha = $ha;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.activate_ha_for_admin')->subject('Activation HomeAdvisor')->with([
            'user' => $this->user,
            'ha' => $this->ha,
            'link' => $this->link,
        ]);
    }
}
