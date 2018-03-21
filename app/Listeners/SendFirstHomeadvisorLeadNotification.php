<?php

namespace App\Listeners;

use App\Events\FirstHomeadvisorLead;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Services\HomeAdvisorService;
use App\Events\SaveLeadFromHomeadvisor;
use App\Jobs\SendFirstLead;
use App\Jobs\SendLeadText;
use App\Jobs\SendFollowUpText;
use App\User;
use Carbon\Carbon;

class SendFirstHomeadvisorLeadNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SaveLeadFromHomeadvisor $event
     * @return void
     */
    public function handle(SaveLeadFromHomeadvisor $event)
    {
        $ha = $event->user->homeadvisors;
        if ($event->user->teams->clients()->where('source', 'HomeAdvisor')->count() == 1 && $event->lead_exists) {
            $owner = User::where('owner', true)->first();
            SendFirstLead::dispatch($event->user, $owner, $ha)->onQueue('emails');
        }

        if ( ! empty($ha->active) && ! empty($ha->text) && ! empty($event->client->phone)) {
            $this->textCreate($event->user, $event->client, $ha);
        }
    }

    public function textCreate($user, $client, $ha)
    {
        $dialog = $user->dialogs()->create([
            'clients_id' => $client->id,
            'text' => '',
            'file' => ! empty($ha->file) ? $ha->file : '',
            'my' => true,
            'status' => 2,
        ]);

        $text = HomeAdvisorService::createText($user, $client, $ha, $dialog);
        $dialog->update(['text' => $text]);

        $row = [
            'phone' => $client->phone,
        ];

        if (strpos($dialog->text, '[$FirstName]') !== false) {
            $row['firstname'] = $client->firstname;
        }

        if (strpos($dialog->text, '[$LastName]') !== false) {
            $row['lastname'] = $client->lastname;
        }

        $phones[] = $row;

        SendLeadText::dispatch($dialog, $phones, $user)->onQueue('texts');

        if ( ! empty($ha->first_followup_active) && ! empty($ha->first_followup_text)) {
            $followup_delay = $ha->first_followup_delay;
            $date = Carbon::now()->addMinutes($followup_delay);
            $user_date = Carbon::now()->addMinutes($followup_delay)->subHour($user->offset);

            if ($user_date->hour <= 6) {
                $date->addHour(6 - $user_date->hour);
                $data->minute = 1;
            }
            
            $delay = Carbon::now()->diffInSeconds($date);
            SendFollowUpText::dispatch($dialog, $phones, $user, $ha->first_followup_text)->delay($delay)->onQueue('texts');
        }

        if ( ! empty($ha->second_followup_active) && ! empty($ha->second_followup_text)) {
            $followup_delay = $ha->second_followup_delay;
            $date = Carbon::now()->addMinutes($followup_delay);
            $user_date = Carbon::now()->addMinutes($followup_delay)->subHour($user->offset);

            if ($user_date->hour <= 6) {
                $date->addHour(6 - $user_date->hour);
                $data->minute = 1;
            }
            
            $delay = Carbon::now()->diffInSeconds($date);

            SendFollowUpText::dispatch($dialog, $phones, $user, $ha->second_followup_text)->delay($delay)->onQueue('texts');
        }
    }
}
