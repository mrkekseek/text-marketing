<?php

namespace App\Listeners;

use App\Events\FirstLead;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendFirstLeadForAdmin;
use App\Notifications\SendFirstLeadForUser;

class SendFirstLeadNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FirstLead  $event
     * @return void
     */
    public function handle(FirstLead $event)
    {
        Notification::send($event->owner, new SendFirstLeadForAdmin($event->user));
        Notification::send($event->user, new SendFirstLeadForUser($event->user));
    }
}
