<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SignUpForUser extends Mailable
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
        $link = $this->url.'/magic/'.md5($this->user->id.$this->user->email).'/send';
        $project = $this->name;
        
        return $this->markdown('emails.signup_for_user')
        ->subject('Thanks You for Signing Up')
        ->with([
                'user' => $this->user,
            ]);
    }
}
