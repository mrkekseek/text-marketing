<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Libraries\Api;
use App\Dialog;

class SendLeadText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dialog;
    protected $clients;
    protected $text;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Dialog $dialog, $clients, $text, $user)
    {
        $this->dialog = $dialog;
        $this->clients = $clients;
        $this->text = $text;
        $this->user = $user;
        $response = Api::dialog($this->dialog->id, $this->clients, $this->text, $this->user->company, $this->user->offset);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Api::dialog($this->dialog->id, $this->clients, $this->text, $this->company, $this->review->user->offset);
        if ($response['code'] == 200) {
            $dialog->update(['status' => $response['finish']]);
        }
    }
}
