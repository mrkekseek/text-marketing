<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Message;
use App\Libraries\Api;

class SendMarketingText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $clients;
    protected $text;
    protected $company;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Message $message, $clients, $text, $company)
    {
        $this->review = $message;
        $this->clients = $clients;
        $this->text = $text;
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Api::message($this->message->id, $this->clients, $this->text, $this->company);
    }
}
