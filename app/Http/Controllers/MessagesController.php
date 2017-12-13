<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;

class MessagesController extends Controller
{
	public function info($id = false)
	{
		return Message::find($id);
	}

    public function save($id = false, $post = [])
    {
    	$message = Message::firstOrNew(['id' => empty($id) ? 0 : $id]);
    	$message->users_id = auth()->user()->id;
    	$message->lists_id = ! empty($post['lists_id']) ? $post['lists_id'] : 0;
    	$message->text = $post['text'];
    	$message->file = '';
    	$message->schedule = $post['schedule'];
    	$message->switch = $post['switch'];
    	$message->date = $this->getDate($post);
    	$message->status = 2;
    	$message->active = 1;
    	$message->save();
    	return $this->info($message->id);
    }

    public function getDate($post)
    {
    	$date = explode('T', $post['date'])[0];
    	$time = explode('T', $post['time'])[1];
    	$time = substr($time, 0, strpos($time, '.'));
    	return strtotime($date.' '.$time);
    }
}
