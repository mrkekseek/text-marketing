<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Mail\AlertDelaySend;

class SendAlertDelay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $alerts = [];
        foreach ($this->user->reviews as $review) {
            $seances = $review->seances()->alerts($this->user->surveys->alerts_stars, $this->user->surveys->alerts_often)->with([
                'answers' => function($q) {
                    $q->where('question_id', 1);
                }
            ])->get();

            foreach ($seances as $seance) {
                $alerts[] = $seance;
                $seance->update([
                    'alert' => true,
                ]);
            }
        }
        
        if ( ! empty($alerts)) {
            $emails = [];
            if ( ! empty($this->user->surveys->alerts_emails)) {
                $emails = explode(',', $this->user->surveys->alerts_emails);
            }
            $emails[] = $this->user->email;
            $emails = array_unique($emails);
            foreach ($emails as $email) {
                Mail::to($email)->send(new AlertDelaySend($alerts));
            }
        }
    }
}
