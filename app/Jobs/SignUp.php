<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignUpForUser;
use App\Mail\SignUpForAdmin;
use App\Mail\SignUpForUserHa;
use App\Mail\SupportMail;
use App\Libraries\Api;
use App\GeneralMessage;
use App\DefaultText;

class SignUp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $owner;
    protected $user;
    protected $url;
    protected $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($owner, $user)
    {
        $this->owner = $owner;
        $this->user = $user;
        $this->url = config('app.url');
        $this->name = config('app.name');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->owner)->send(new SignUpForAdmin($this->user, $this->url, $this->name));
        if ($this->user->plans_id == 'home-advisor-'.strtolower(config('app.name'))) {
            $default_text = DefaultText::first();
		    $text = $default_text->thank_you_signup;

            $global_dialog = new GeneralMessage();
            $global_dialog->type = 'thank_you_signup';
            $global_dialog->phone = $this->user->phone;
            $global_dialog->firstname = $this->user->firstname;
            $global_dialog->lastname = $this->user->lastname;
            $global_dialog->text = $text;
            $global_dialog->my = 1;
            $global_dialog->status = 0;
            $global_dialog->save();

            $phones[] = ['phone' => $this->user->phone];

            Api::generalMessages($global_dialog->id, $phones, $text, 'ContractorTexter', $this->user->offset);

            //Mail::to($this->user->email)->send(new SignUpForUserHa($this->user, $this->url, $this->name));
        } else {
            Mail::to($this->user->email)->send(new SignUpForUser($this->user, $this->url, $this->name));
        }
    }
}
