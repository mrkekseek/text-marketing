<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\FirstLead;
use App\Jobs\SendFirstLead;

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
        SendFirstLead::dispatch($event->user, $event->owner, $event->ha)->onQueue('emails');
    }
}
