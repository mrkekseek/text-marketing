<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SignUpEmailForAdmin extends Notification
{
    use Queueable;

    public $config;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($config, $user)
    {
        $this->config = $config;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $link = $this->config['url'].'/magic/'.md5($notifiable->id.$notifiable->email).'/list';
        $project = $this->config['name'];
        return (new MailMessage)
            ->subject('New Sign Up at '.$project)
            ->markdown('emails.signup_for_admin', [
                'user' => $this->user,
                'link' => $link,
                'project' => $project
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
