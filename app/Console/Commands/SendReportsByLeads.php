<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\User;
use App\Alert;
use App\Notifications\WeeklyReportsByLeads;
use App\Jobs\SendWeeklyRecap;
use App\Libraries\ApiValidate;

class SendReportsByLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:weeklyReports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = User::usersHomeAdvisor();

        foreach ($data as $user) {
            if ($user->id == 45) {
                $result = [
                    'clients_count' => ! empty($user->teams->clients_count) ? $user->teams->clients_count : 0,
                    'clicked_count' => 0,
                    'reply_count' => 0,
                    'user_hash' => md5($user->id.$user->created_at),
                ];

                if ( ! empty($user->teams->clients)) {
                    foreach ($user->teams->clients as $client) {
                        if ( ! empty($client->dialogs_clicked_count)) {
                            $result['clicked_client'][] = $client->firstname;
                            $result['clicked_count']++;
                        }

                        if ( ! empty($client->dialogs_reply_count)) {
                            $result['reply_client'][] = $client->firstname;
                            $result['reply_count']++;
                        }
                    }
                }

                $result['reply_client'] = ! empty($result['reply_client']) ? implode(', ', $result['reply_client']) : '';
                $result['clicked_client'] = ! empty($result['clicked_client']) ? implode(', ', $result['clicked_client']) : '';

                $text = 'Hi '.$user->firstname.' - this week you got '.$result['clients_count'].' HA Leads. '.$result['clicked_count'].' clicked your link, '.$result['reply_count'].' texted you back. Thanks!';

                if ( ! empty($user->phone)) {
                    $phones[]['phone'] = $user->phone;
                    $temp[] = $user->phone;
                }

                $homeadvisor = $user->homeadvisors;

                if ( ! empty($homeadvisor->additional_phones)) {
                    $numbers = explode(',', $homeadvisor->additional_phones);
                    foreach ($numbers as $number) {
                        $phone = $this->createPhone($number);
                        if ( ! empty($phone)) {
                            $phones[]['phone'] = $phone;
                            $temp[] = $phone;
                        }
                    }
                }

                if ( ! empty($phones)) {
                    $data = [
                        'user_id' => $user->id,
                        'phone' => implode(',', $temp),
                        'text' => $text,
                    ];
                    $temp = [];
                    $alert = Alert::create($data);
                    SendWeeklyRecap::dispatch($alert, $phones, $text, $user)->onQueue('texts');
                    $phones = [];
                }
            }
            //Notification::send($user, new WeeklyReportsByLeads($user->firstname, $result));
        }
    }

    public function createPhone($number)
    {
        $phone = str_replace(['-', '(', ')', ' ', '.'], '', $number);
        if (ApiValidate::phoneFormat($phone)) {
            return $phone;
        }
        return false;
    }
}
