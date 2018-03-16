<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Libraries\Api;
use App\Alert;

class SendAlertClick implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $alert;
    public $phones;
    public $text;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Alert $alert, $phones, $text, $user, $reply_viewed = false)
    {
        $this->alert = $alert;
        $this->phones = $phones;
        $this->text = $text;
        $this->user = $user;
        $this->reply_viewed = $reply_viewed;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ( ! $this->reply_viewed) {
            $response = Api::alert($this->alert->id, $this->phones, $this->text, 'ContractorTexter', $this->user->offset);
        }
    }
}
