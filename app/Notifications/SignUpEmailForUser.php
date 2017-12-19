<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SignUpEmailForUser extends Notification
{
    use Queueable;

    public $config;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($config)
    {
       $this->config = $config;
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
        $link = $this->config['url'].'/magic/'.md5($notifiable->id.$notifiable->email).'/send';
        $project = $this->config['name'];

        return (new MailMessage)
            ->subject('Thanks from '.config('app.name'))
            ->markdown('emails.signup_for_user', [
                'user' => $notifiable,
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
