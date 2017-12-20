<?php

namespace App\Listeners;

use App\Events\SignUp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\SignUpEmailForUser;
use App\Notifications\SignUpEmailForUserHA;
use App\Notifications\SignUpEmailForAdmin;
use Illuminate\Support\Facades\Notification;

class SendSignUpNotification
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
     * @param  Event  $event
     * @return void
     */
    public function handle(SignUp $event)
    {
        if ($event->user->plans_id == 'home-advisor-'.strtolower(config('app.name'))) {
            Notification::send($event->user, new SignUpEmailForUserHA($event->config));
        }else {
            Notification::send($event->user, new SignUpEmailForUser($event->config));
        }

        Notification::send($event->owner, new SignUpEmailForAdmin($event->config, $event->user));
    }
}
