<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WeeklyReportsByLeads extends Notification
{
    use Queueable;

    public $firstname;
    public $result;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($firstname, $result)
    {
        $this->firstname = $firstname;
        $this->result = $result;
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
                    ->subject('Weekly Recap')
                    ->from('Uri@ContractorTexter.com')
                    ->markdown('emails.weekly_reports_by_leads', [
                        'firstname' => $this->firstname,
                        'result' => $this->result,
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
