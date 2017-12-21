<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SignUpForUserHa extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $url;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $url, $name)
    {
        $this->user = $user;
        $this->url = $url;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $project = $this->name;

        return $this->markdown('emails.signup_for_user_ha')
        ->subject('Thanks from '.$project)
        ->with([
                'user' => $this->user,
                'project' => $project
            ]);
    }
}
