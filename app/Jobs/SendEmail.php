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
    protected $company_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client, $seance, $survey, $company_name)
    {
        $this->client = $client;
        $this->seance = $seance;
        $this->survey = $survey;
        $this->company_name = ! empty($company_name) ? $company_name : '';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->survey['email'] = str_replace('[$FirstName]', $this->client['firstname'], $this->survey['email']);
        $this->survey['subject'] = str_replace('[$FirstName]', $this->client['firstname'], $this->survey['subject']);
        Mail::to($this->client['email'])->send(new SurveySend($this->seance, $this->survey, $this->company_name));
    }
}
