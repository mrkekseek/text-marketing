<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Seance;
use App\Survey;

class SurveySend extends Mailable
{
    use Queueable, SerializesModels;

    public $seance;
    public $survey;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($seance, $survey)
    {
        $this->seance = $seance;
        $this->survey = $survey;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.survey')->subject($this->survey['subject'])->with(['id' => $this->seance->id, 'text' => $this->survey['email']]);
    }
}
