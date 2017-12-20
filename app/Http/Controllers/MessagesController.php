<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Http\Requests\MessageCreateRequest;

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
        $data = $request->only(['lists_id', 'text', 'file', 'schedule', 'switch', 'date']);
        $data['lists_id'] = implode(',', $data['lists_id']);
        $data['date'] = $this->getDate($request->only(['time', 'date']));
        $data['status'] = 2;
        $data['active'] = true;
        auth()->user()->messages()->create($data);
        return $this->message('Message was successfully saved', 'success');
    }

    public function remove($id = false)
    {
        Message::destroy($id);
        return $this->message(__('Message was successfully removed'), 'success');
    }

    public function getDate($data)
    {
    	$date = explode('T', $data['date'])[0];
    	$time = explode('T', $data['time'])[1];
    	$time = substr($time, 0, strpos($time, '.'));
    	return strtotime($date.' '.$time);
    }
}
