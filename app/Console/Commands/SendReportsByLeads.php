<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WeeklyReportsByLeads;

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
            $result = [
                'clients_count' => ! empty($user->teams->clients_count) ? $user->teams->clients_count : 0,
                'clicked_count' => 0,
                'reply_count' => 0,
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
            
            Notification::send($user, new WeeklyReportsByLeads($user->firstname, $result));
        }
    }
}
