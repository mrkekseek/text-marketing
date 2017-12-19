<?php

namespace App\Http\Controllers;

use App\Client;
use App\User;
use App\Seance;
use App\ListClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientsController extends Controller
{
    public function all()
	{
		return User::find(auth()->user()->id)->clients;
	}

	public function leads()
	{
		return Client::where('users_id', auth()->user()->id)->where('source', 'HomeAdvisor')->get();
	}

	public function info(Request $request, $id = false)
	{
		$data['client'] = Client::find($id);
		$data['seances'] = Seance::where('clients_id', $id)->where('completed', 1)->get();
		return $data;
	}

	public function save(Request $request, $id = false)
	{
		$client = Client::firstOrNew(['id' => empty($id) ? 0 : $id]);
		$client->users_id = auth()->user()->id;
		$client->firstname = $request['firstname'];
		$client->lastname = ! empty($request['lastname']) ? $request['lastname'] : '';
		$client->phone = $this->editPhone($request['phone']);
		$client->view_phone = $request['phone'];
		$client->email = trim($request['email']);
		$client->save();

		if ( ! empty($request['lists_id'])) {
			$client->lists()->detach($request['lists_id']);
			$client->lists()->attach($request['lists_id']);
		}

		$this->message(__('Client was successfully saved'), 'success');

		return $client->id;
	}

	public function addToList(Request $request, $id = false)
	{
		foreach ($request->all() as $row) {
			$client = Client::find($row['id']);
			$client->lists()->detach($id);
			$client->lists()->attach($id);
		}
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
