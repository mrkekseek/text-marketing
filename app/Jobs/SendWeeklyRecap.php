<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Libraries\Api;
use App\User;
use App\Alert;

class SendWeeklyRecap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $alert;
    protected $phones;
    protected $text;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Alert $alert, $phones, $text, $user)
    {
        $this->alert = $alert;
        $this->phones = $phones;
        $this->text = $text;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Api::alert($this->alert->id, $this->phones, $this->text, 'ContractorTexter', $this->user->offset);
    }
}
