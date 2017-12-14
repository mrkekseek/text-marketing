<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dialog;
use App\Client;
use App\Libraries\ApiServer;


class DialogsController extends Controller
{
    public function all()
	{
		return Dialog::has('clients')->where('users_id', auth()->user()->id)->get()->unique('clients_id')->toArray();
	}

	public function info($id = false)
	{
		return Dialog::where('clients_id', $id)->where('users_id', auth()->user()->id)->get();
	}

	public function save($id = false, $post = [])
	{
		$dialog = Dialog::firstOrNew(['id' => empty($id) ? 0 : $id]);
		$dialog->users_id = auth()->user()->id;
		$dialog->clients_id = $post['client']['clients_id'];
		$dialog->text = auth()->user()->company_name.': '.$post['text'].' Txt STOP to OptOut';
		$dialog->my = 1;
		$dialog->status = 2;
		$dialog->save();

		$this->message(__('Message was send'), 'success');
		return $dialog;
	}
}
