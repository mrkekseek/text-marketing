<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendFirstLeadForAdmin;
use App\Notifications\SendFirstLeadForUser;
use App\User;
use App\Homeadvisor;

class SendFirstLead implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $owner;
    public $ha;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, User $owner, Homeadvisor $ha)
    {
        $this->user = $user;
        $this->owner = $owner;
        $this->ha = $ha;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::send($this->owner, new SendFirstLeadForAdmin($this->user, $this->ha));
        Notification::send($this->user, new SendFirstLeadForUser($this->user));
    }
}
