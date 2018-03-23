<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Libraries\Api;
use App\Dialog;
use App\User;
use App\Setting;
use App\Client;
use DivArt\ShortLink\Facades\ShortLink;

class SendFollowUpText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dialog;
    protected $clients;
    protected $user;
    protected $test;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Dialog $dialog, $clients, User $user, $text)
    {
        $this->dialog = $dialog;
        $this->clients = $clients;
        $this->user = $user;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Client::where('phone', $this->clients)->first();
        if (empty($this->dialog->clicked) && empty($this->dialog->reply) && $this->dialog->status == 1 && ! $client->followup_disabled) {
            $dialog = $this->user->dialogs()->create([
                'clients_id' => $this->dialog->clients_id,
                'text' => '',
                'file' => '',
                'my' => true,
                'status' => 2,
            ]);

            $dialog->update(['text' => $this->createText($this->dialog->text, $this->text, $dialog->id)]);
            
            $response = Api::followUp($dialog->id, $this->clients, $dialog->text, $this->user->company_name, $this->user->offset);
            if ($response['code'] != 200) {
                $dialog->update(['status' => 0]);
            }

            foreach ($response['data'] as $client) {
                if ( ! empty($client['finish'])) {
                    $dialog->update(['status' => 0]);
                }
            }
        }
    }

    public function createText($text, $followUpText, $id = false)
    {
        $linkPos = strpos($text, 'bit.ly/');
        if ($linkPos !== false) {
            $originLink = substr($text, $linkPos, 14);
            $longLink = ShortLink::expand($originLink);
            $temp = explode('/', $longLink);
            $link = ShortLink::bitly(config('app.url').'/magic/'.$id.'/bit.ly/'.array_pop($temp), false);
            $followUpText = str_replace('[$Link]', $link, $followUpText);
        }

        return $followUpText;
    }
}
