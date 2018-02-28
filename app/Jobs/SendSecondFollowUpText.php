<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSecondFollowUpText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->dialog = $dialog;
        $this->clients = $clients;
        $this->user = $user;
        $this->test = $this->createText($this->dialog->text);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->dialog->clicked) && empty($this->dialog->reply) && $this->dialog->status == 1) {

            $dialog =  $this->user->dialogs()->create([
                'clients_id' => $this->dialog->clients_id,
                'text' => '',
                'file' => '',
                'my' => true,
                'status' => 2,
            ]);

            $dialog->update(['text' => $this->createText($this->dialog->text, $dialog->id)]);

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
}
