<?php

namespace App\Http\Controllers;

use App\Client;
use App\User;
use App\Seance;
use App\ListClient;
use App\ContactList;
use App\Http\Services\UsersService;
use App\Http\Requests\ClientCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientsController extends Controller
{
    public function all()
	{
		return auth()->user()->teams->clients;
	}

	public function leads()
	{
		return auth()->user()->teams->clients()->where('source', 'HomeAdvisor')->with('dialogs')->get();
	}

	public function info(Request $request, $id = false)
	{
		$data['client'] = Client::find($id);
		$data['seances'] = Seance::where('clients_id', $id)->where('completed', 1)->get();
		return $data;
	}

	public function reportsReviews(Client $client)
	{
		return $client->seances;
	}

	public function create(ClientCreateRequest $request)
	{
		$data = $request->only(['firstname', 'lastname', 'view_phone', 'email']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data['team_id'] = auth()->user()->teams_id;
		$data = array_filter($data, 'strlen');
		$client = Client::create($data);

		$this->message('Client was successfully saved', 'success');
		return $client->id;
	}

	public function createForList(ClientCreateRequest $request, ContactList $list)
	{
		$data = $request->only(['firstname', 'lastname', 'view_phone', 'email']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data['team_id'] = auth()->user()->teams_id;
		$data = array_filter($data, 'strlen');
		$client = Client::create($data);

		$list->clients()->syncWithoutDetaching([$client->id]);
		$this->message('Client was successfully saved', 'success');
		return $client->id;
	}

	public function updateForList(ClientCreateRequest $request, ContactList $list, Client $client)
	{
		$data = $request->only(['firstname', 'lastname', 'view_phone', 'email']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data['team_id'] = auth()->user()->teams_id;
		$data = array_filter($data, 'strlen');
		$client->update($data);

		$list->clients()->syncWithoutDetaching([$client->id]);
		$this->message('Client was successfully saved', 'success');
		return $client->id;
	}

	public function update(ClientCreateRequest $request, $id)
	{
		$data = $request->only(['firstname', 'lastname', 'view_phone', 'email']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data = array_filter($data, 'strlen');
		$client = Client::find($id)->update($data);

		return $this->message('Client was successfully saved', 'success');
	}

	public function removeFromList(ContactList $list, Client $client)
	{
		$list->clients()->detach([$client->id]);
		return $this->message(__('Client was successfully removed from list.'), 'success');
	}

	public function remove($id)
	{
		Client::destroy($id);
		return $this->message(__('Client was successfully removed'), 'success');
	}

	public function addToList(Request $request, $id = false)
	{
		foreach ($request->all() as $row) {
			$client = Client::find($row['id']);
			$client->lists()->detach($id);
			$client->lists()->attach($id);
		}
	}

	public function editPhone($phone)
	{
		return str_replace([' ', '-', '.', ',', '_', '(', ')', '+'], '', $phone);
	}
}
