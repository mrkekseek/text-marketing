<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignUpForUser;
use App\Mail\SignUpForAdmin;
use App\Mail\SignUpForUserHa;

class SignUp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $owner;
    protected $user;
    protected $config;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($owner, $user)
    {
        $this->owner = $owner;
        $this->user = $user;
        $this->config = config('app');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->owner)->send(new SignUpForAdmin($this->user, $this->config));
        /*if ($this->user->plans_id == 'home-advisor-'.strtolower(config('app.name'))) {
            Mail::to($this->user->email)->send(new SignUpForUserHa($this->user, $this->config));
        } else {
            Mail::to($this->user->email)->send(new SignUpForUser($this->user, $this->config));
        }*/
    }
}
