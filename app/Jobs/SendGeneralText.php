<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Libraries\Api;
use App\NewUser;
use DivArt\ShortLink\Facades\ShortLink;

class SendGeneralText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $new_user;
    protected $phones;
    protected $text;
    protected $company;
    protected $offset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(NewUser $new_user, $phones, $text, $company, $offset)
    {
        $this->new_user = $new_user;
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
        $text = $this->createText($this->text, $this->new_user->id);
        Api::generalMessages($this->new_user->id, $this->phones, $text, $this->company, $this->offset);
    }

    public function createText($text, $id)
    {
        $linkPos = strpos($text, 'bit.ly/');
    	if ($linkPos !== false) {
    		$originLink = substr($text, $linkPos, 14);
    		$fakeLink = ShortLink::bitly(config('app.url').'/general/'.$id.'/'.$originLink, false);
    		$text = str_replace($originLink, $fakeLink, $text);
        }

        return $text;
    }
}
