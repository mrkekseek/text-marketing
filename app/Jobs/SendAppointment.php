<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Libraries\Api;

class SendAppointment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appointment;
    protected $phones;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($appointment, $phones, $user)
    {
        $this->appointment = $appointment;
        $this->phones = $phones;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Api::appointment($this->appointment->id, $this->phones, $this->appointment->text, $this->user->company_name, $this->user->offset);
        if ($response['code'] != 200) {
            $this->appointment->update(['finish' => true]);
        }
    }
}
