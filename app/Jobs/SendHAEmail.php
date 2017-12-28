<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivateHa;
use App\User;
use App\Homeadvisor;
use App\Link;

class SendHAEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $owner;
    public $ha;
    public $link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, User $owner, Homeadvisor $ha, Link $link)
    {
        $this->user = $user;
        $this->owner = $owner;
        $this->ha = $ha;
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->owner)->send(new ActivateHa($this->user, $this->ha, $this->link));
    }
}
