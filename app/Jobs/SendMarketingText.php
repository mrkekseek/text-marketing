<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Text;
use App\Message;
use App\ContactList;
use App\Libraries\Api;
use App\Http\Services\MessagesService;
use Carbon\Carbon;

class SendMarketingText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $text;
    protected $clients;
    protected $message;
    protected $company;
    protected $date;
    protected $token;
    protected $attachment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Text $text, $clients, Message $message, $company, $token)
    {
        $this->text = $text;
        $this->clients = $clients;
        $this->message = $message;
        $this->company = $company;
        $this->token = $token;
        $this->attachment = ! empty($this->message->file) ? config('app.url').'/'.$this->message->file : '';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->token == $this->message->token->toDateTimeString()) {
            $delay = $this->getDelay();
            if ($delay === false) {
                $this->message->update([
                    'token' => Carbon::yesterday(),
                ]);
            } else {
                $date = $this->message->date->addSeconds($delay);
                $this->message->update([
                    'token' => $date,
                    'date' => $date,
                ]);
            }

            $response = Api::message($this->text->id, $this->clients, $this->message->text, $this->company, $this->message->user->offset, $this->attachment);
            if ($response['code'] == 200) {
                MessagesService::receivers($this->text, $response['data']);
            } else {
                $this->text->update(['message' => ! empty($response['message']) ? $response['message'] : '']);
            }

            if ( ! empty($this->message->schedule) && $this->message->switch > 1 && $this->message->finish_date >= $this->message->date && $this->message->active) {
                $this->sendAgaine($delay);
            }
        }
    }

    public function sendAgaine($delay)
    {
        $text = MessagesService::createText($this->message, $this->clients);
        $clientsObj = $this->sendClients($this->message->lists_id);

        foreach ($clientsObj as $client) {
            $row = [
                'phone' => $client->phone,
            ];

            if (strpos($this->message->text, '[$FirstName]') !== false) {
                $row['firstname'] = $client->firstname;
            }

            if (strpos($this->message->text, '[$LastName]') !== false) {
                $row['lastname'] = $client->lastname;
            }

            $phones[] = $row;
            MessagesService::createReceiver($text, $client);
        }

        SendMarketingText::dispatch($text, $phones, $this->message, $this->message->user->company_name, $this->message->date->toDateTimeString())->onQueue('texts')->delay($delay);
    }

    public function getDelay()
    {
        switch($this->message->switch) {
            case 2: $delay = Carbon::now()->diffInSeconds($this->message->date->addDay()); break;
            case 3: $delay = Carbon::now()->diffInSeconds($this->message->date->addWeek()); break;
            case 4: $delay = Carbon::now()->diffInSeconds($this->message->date->addMonth()); break;
            case 5: $delay = Carbon::now()->diffInSeconds($this->message->date->addDays($this->message->x_day)); break;
            default: $delay = false;
        }
        return $delay;
    }

    public function sendClients($list_ids)
    {
        $result = [];
        $exists = [];
        $list_ids = explode(',', $list_ids);
        $lists = ContactList::whereIn('id', $list_ids)->with('clients')->get();

        foreach ($lists as $list) {
            foreach ($list->clients as $client) {
                if ( ! in_array($client->phone, $exists)) {
                    $result[] = $client;
                    $exists[] = $client->phone;
                }
            }
        }

        return $result;
    }
}
