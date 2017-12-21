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
    public $config;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $config)
    {
        $this->user = $user;
        $this->config = $config;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link = $this->config['url'].'/magic/'.md5($this->user->id.$this->user->email).'/send';
        $project = $this->config['name'];
        
        return $this->markdown('emails.signup_for_user')
        ->subject('Thanks from '.$this->config['name'])
        ->with([
                'user' => $this->user,
                'link' => $link,
                'project' => $project
            ]);
    }
}
