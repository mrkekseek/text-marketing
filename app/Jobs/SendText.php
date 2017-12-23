<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Review;
use App\Libraries\Api;

class SendText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $review;
    protected $clients;
    protected $text;
    protected $company;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Review $review, $clients, $text, $company)
    {
        $this->review = $review;
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
        $response = Api::review($this->review->id, $this->clients, $this->text, $this->company);

        if( ! file_exists('logs')) {
            mkdir('logs', 0777);
        }
        file_put_contents('logs/logger.txt', date('[Y-m-d H:i:s] ').': RESPONSE'.print_r($data, true).PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
