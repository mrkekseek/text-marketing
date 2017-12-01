<?php

namespace App\Http\Controllers;

use App\Client;
use App\User;
use App\Seance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientsController extends Controller
{
    public function all()
	{
		return User::find(Auth::user()->id)->clients;
	}

	public function info($id = false)
	{
		$data['client'] = Client::find($id);
		$data['seances'] = Seance::where('clients_id', $id)->where('completed', 1)->get();
		return $data;
	}

	public function save($id = false, $post = [])
	{
		$client = Client::firstOrNew(['id' => empty($id) ? 0 : $id]);
		$client->users_id = Auth::user()->id;
		$client->firstname = $post['firstname'];
		$client->lastname = ! empty($post['lastname']) ? $post['lastname'] : '';
		$client->phone = $this->editPhone($post['phone']);
		$client->view_phone = $post['phone'];
		$client->email = trim($post['email']);
		$client->save();

		$this->message(__('Client was successfully saved'), 'success');

		return $this->all();
	}

	public function remove($id = false)
	{
		Client::destroy($id);
		return $this->message(__('Client was successfully removed'), 'success');
	}

	public function editPhone($phone)
	{
		return str_replace([' ', '-', '.', ',', '_', '(', ')', '+'], '', $phone);
	}
}
