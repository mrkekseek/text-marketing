<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Libraries\Api;
use App\GeneralMessage;
use App\Homeadvisor;
use App\Setting;
use App\User;

class SendHomeadvisorActivationDelay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $activation_delay;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $activation_delay = false)
    {
        $this->user = $user;
        $this->activation_delay = $activation_delay;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->user->teams->clients()->where('source', 'HomeAdvisor')->count() == 0) {
            $data = Setting::where('text_code', 'twodays')->first();
            $text = $data['text'];
            $type = 'twodays';

            if ($this->activation_delay == 4) {
                $data = Setting::where('text_code', 'fourdays')->first();
                $text = $data['text'];
                $type = 'fourdays';
            }

            $message = new GeneralMessage();
            $message->type = $type;
            $message->phone = $this->user->phone;
            $message->firstname = $this->user->firstname;
            $message->lastname = $this->user->lastname;
            $message->text = $text;
            $message->my = 1;
            $message->status = 0;
            $message->save();

            $phones[] = [
                'phone' => $this->user->phone,
            ];
        
            Api::generalMessages($message->id, $phones, $text, 'ContractorTexter', $this->user->offset);
        }
    }
}