<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Text;
use App\Libraries\Api;
use App\Http\Services\MessagesService;

class SendMarketingText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $text;
    protected $clients;
    protected $message;
    protected $company;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Text $text, $clients, $message, $company)
    {
        $this->text = $text;
        $this->clients = $clients;
        $this->message = $message;
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Api::message($this->text->id, $this->clients, $this->message, $this->company, $this->text->message->user->offset);
        if ($response['code'] == 200) {
            MessagesService::receivers($this->text, $response['data']);
        } else {
            $this->text->update(['message' => ! empty($response['message']) ? $response['message'] : '']);
        }
    }
}
