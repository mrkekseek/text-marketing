<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Libraries\Api;
use App\GeneralMessage;

class SendGeneralText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $phones;
    protected $text;
    protected $company;
    protected $offset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GeneralMessage $message, $phones, $text, $company, $offset)
    {
        $this->message = $message;
        $this->phones = $phones;
        $this->text = $text;
        $this->company = $company;
        $this->offset = $offset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Api::generalMessages($this->message->id, $this->phones, $this->text, $this->company, $this->offset);
    }
}
