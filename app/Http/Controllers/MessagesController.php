<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\ContactList;
use App\Http\Requests\MessageCreateRequest;
use Carbon\Carbon;
use App\Jobs\SendMarketingText;

class MessagesController extends Controller
{
	public function info($id = false)
	{
		return Message::find($id);
	}

    public function all()
    {
        return auth()->user()->messages;
    } 

    public function create(MessageCreateRequest $request)
    {
        $data = $request->only(['lists_id', 'text', 'file', 'schedule', 'switch']);
        $data['lists_id'] = implode(',', $data['lists_id']);
        $data['date'] = $this->getDate($request->schedule, $request->time);
        $data['status'] = 2;
        $data['active'] = true;
        $message = auth()->user()->messages()->create($data);
        $this->sendText($message);
        return $this->message('Message was successfully saved', 'success');
    }

    public function sendText($message)
    {
        $this->sendClients($message->lists_id, $message->text); die;

        $delay = Carbon::now()->diffInSeconds($message->date);
        SendMarketingText::dispatch($message, $this->sendClients($message->lists_id, $message->text), $message->text, auth()->user()->company_name)->onQueue('texts')->delay($delay);
    }

    public function sendClients($list_ids, $text)
    {
        $result = [];
        $list_ids = explode(',', $list_ids);
        $lists = ContactList::whereIn('id', $list_ids)->with('clients')->get()->toArray();

        foreach ($lists as $list) {
            foreach ($list['clients'] as $client) {
                $row = [
                    'phone' => $client['phone'],
                ];

                if (strpos($text, '[$FirstName]') !== false) {
                    $row['firstname'] = $client['firstname'];
                }

                if (strpos($text, '[$LastName]') !== false) {
                    $row['lastname'] = $client['lastname'];
                }

                $result[] = $row;
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
}
