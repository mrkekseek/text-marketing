<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\SurveySend;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;
    protected $seance;
    protected $survey;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client, $seance, $survey)
    {
        $this->client = $client;
        $this->seance = $seance;
        $this->survey = $survey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->client['email'])->send(new SurveySend($this->seance, $this->survey));
    }
}
