<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Text;
use App\Receiver;
use App\ContactList;
use App\Http\Requests\MessageCreateRequest;
use Carbon\Carbon;
use App\Jobs\SendMarketingText;
use App\Libraries\ApiValidate;

class MessagesController extends Controller
{
	public function info($id = false)
	{
		return Message::find($id);
	}

    public function all()
    {
        return auth()->user()->messages()->orderBy('updated_at', 'desc')->with(['texts.receivers'])->get();
    } 

    public function create(MessageCreateRequest $request)
    {
        $data = $request->only(['lists_id', 'text', 'file', 'schedule', 'switch']);
        $data['lists_id'] = implode(',', $data['lists_id']);
        $data['date'] = $this->getDate($request->schedule, $request->time);
        $data['active'] = true;
        $message = auth()->user()->messages()->create($data);
        $this->sendText($message);
        return $this->message('Message was successfully saved', 'success');
    }

    public function sendText($message)
    {
        $clients = $this->sendClients($message->lists_id, $message->text);
        $text = $message->texts()->create([
            'phones' => count($clients),
            'message' => '',
            'send_at' => $message->date,
        ]);

        $phones = [];

        foreach ($clients as $client) {
            $row = [
                'phone' => $client->phone,
            ];

            if (strpos($message->text, '[$FirstName]') !== false) {
                $row['firstname'] = $client->firstname;
            }

            if (strpos($message->text, '[$LastName]') !== false) {
                $row['lastname'] = $client->lastname;
            }

            $phones[] = $row;

            $text->receivers()->create([
                'client_id' => $client->id,
                'message' => '',
            ]);
        }

        $delay = Carbon::now()->diffInSeconds($message->date);
        SendMarketingText::dispatch($text, $phones, $message->text, auth()->user()->company_name)->onQueue('texts')->delay($delay);
    }

    public function sendClients($list_ids, $text)
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

    public function remove($id = false)
    {
        Message::destroy($id);
        return $this->message(__('Message was successfully removed'), 'success');
    }

    public function getDate($schedule, $time)
    {
        if ( ! empty($schedule)) {
            return Carbon::create($time['year'], $time['month'], $time['date'], $time['hours'], $time['minutes'], 0, config('app.timezone'));
        }
        return Carbon::now();
    }

    public function textValidate(Request $request)
    {

        $data = $request->all();
        if ( ! ApiValidate::companyExists($data['company'])) {
            return $this->message('This Company Name isn\'t verified');
        }

        if ( ! ApiValidate::companyVerified($data['company'])) {
            return $this->message('Company Name must be verified');
        }

        $text = trim($data['text']);
        if ( ! ApiValidate::messageSymbols($text)) {
            return $this->message('SMS Text contains forbidden characters');
        }

        if ( ! empty($data['clients'])) {
            $length = true;
            $phones = true;
            $limit = true;
            foreach ($data['clients'] as $client) {
                $message = $text;

                if ( ! empty($client['firstname'])) {
                    $message = str_replace('[$FirstName]', $client['firstname'], $message);
                }

                if ( ! empty($client['lastname'])) {
                    $message = str_replace('[$LastName]', $client['lastname'], $message);
                }

                if ( ! ApiValidate::messageLength($message, $data['company'])) {
                    $length = false;
                }

                if ( ! ApiValidate::phoneFormat($client['phone'])) {
                    $phones = false;
                }

                if (ApiValidate::underLimitMarketing($client['id'])) {
                    $limit = false;
                }
            }

            if (empty($length)) {
                return $this->message('SMS Text is too long. Text will not be send');
            }

            if (empty($phones)) {
                return $this->message('Some client\'s phone numbers have wrong format. Text will not be send');
            }

            if (empty($limit)) {
                return $this->message('Some client\'s phone numbers already received texts during last 24h. Text will not be send');
            }
        }

        if (ApiValidate::underBlocking()) {
            return $this->message('You can\'t send texts before 9 AM. You can try to use Schedule Send');
        }

        return 1;
    }
}
