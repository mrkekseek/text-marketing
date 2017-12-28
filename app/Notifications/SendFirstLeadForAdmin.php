<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendFirstLeadForAdmin extends Notification
{
    use Queueable;

    public $user;
    public $ha;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $ha)
    {
        $this->user = $user;
        $this->ha = $ha;
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
        return (new MailMessage)
                    ->subject($this->user['firstname'].' is Ready to Go Live.')
                    ->markdown('emails.first_lead_for_admin', [
                        'user' => $this->user,
                        'ha' => $this->ha,
                    ]);
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
