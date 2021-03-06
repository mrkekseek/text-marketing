<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendReferralForAdmin;
use App\User;

class SendReferral implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $owner;
    public $referral;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, User $owner, $referral)
    {
        $this->user = $user;
        $this->owner = $owner;
        $this->referral = $referral;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::send($this->owner, new SendReferralForAdmin($this->user, $this->referral));
    }
}
