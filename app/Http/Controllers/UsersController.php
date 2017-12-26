<?php

namespace App\Http\Controllers;

use App\User;
use App\Libraries\Api;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\UsersService;
use App\Http\Services\LinksService;
use App\Http\Requests\UsersCreateRequest;
use App\Http\Requests\UsersPasswordRequest;
use App\Http\Requests\PartnersCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function info($id)
    {
    	return User::find($id);
    }

    public function all()
	{
		return User::allUsers();
	}

	public function partners()
	{
		return auth()->user()->partners()->with('urls')->get();
	}

	public function create(UsersCreateRequest $request)
	{
		$data = $request->only(['plans_id', 'firstname', 'lastname', 'email', 'password', 'view_phone']);
		$data['type'] = 2;
		$data['teams_leader'] = true;
		$data['active'] = true;
		$data['password'] = UsersService::password($data['password']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data['teams_id'] = UsersService::createTeam($data);
		$data['offset'] = config('app.offset');
		$data = array_filter($data, 'strlen');

		$user = User::create($data);
		LinksService::create($user);

		return $this->message('Teammate was successfully saved', 'success');
	}

	public function update(UsersCreateRequest $request, $id)
	{
		$data = $request->only(['plans_id', 'firstname', 'lastname', 'email', 'password', 'view_phone']);
		$data['password'] = UsersService::password($data['password']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data = array_filter($data, 'strlen');

		$user = User::find($id)->update($data);

		return $this->message('Teammate was successfully saved', 'success');
	}

	public function partnersCreate(PartnersCreateRequest $request)
	{
		$data = $request->only(['firstname', 'lastname', 'email', 'view_phone']);
		$data['type'] = 2;
		$data['teams_leader'] = false;
		$data['active'] = true;
		$data['password'] = UsersService::password(config('app.name'));
		$data['phone'] = UsersService::phoneToNumber($data);
		$data['teams_id'] = auth()->user()->teams_id;
		$data['plans_id'] = auth()->user()->plans_id;
		$data['offset'] = config('app.offset');
		$data = array_filter($data, 'strlen');

		$user = User::create($data);
		LinksService::create($user);

		$this->message('Partner was successfully saved', 'success');
		return $user;
	}

	public function partnersUpdate(PartnersCreateRequest $request, User $user)
	{
		$data = $request->only(['firstname', 'lastname', 'email', 'view_phone']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data = array_filter($data, 'strlen');

		$user->update($data);

		$this->message('Teammate was successfully saved', 'success');
		return $user;
	}

	public function partnersRemove(User $user)
	{
		$user->links()->delete();

		$user->delete();
		return $this->message('Partner was successfully removed', 'success');
	}

	public function profile(UsersCreateRequest $request)
	{
		$data = $request->only(['firstname', 'lastname', 'email', 'view_phone']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data['offset'] = config('app.offset');
		$data = array_filter($data, 'strlen');

		$user = auth()->user()->update($data);

		return $this->message('Profile was successfully saved', 'success');
	}

	public function remove($id)
	{
		$user = User::find($id);
		$user->links()->delete();

		$user->delete();
		return $this->message('User was successfully removed', 'success');
	}

	public function magic($id)
	{
		$user = User::find($id);
		$user->admins_id = auth()->id();
		$user->save();

		auth()->login($user);
	}

	public function password(UsersPasswordRequest $request)
	{
		$user = auth()->user();
		if (Hash::check($request->old_password, $user->password)) {
			$user->password = UsersService::password($request->password);
			$user->save();
			return $this->message('Password was successfully changed', 'success');
		}

		return $this->message('Old Password is incorrect');
	}

	public function company(Request $request, User $user = null)
	{
		$user = empty($user) ? auth()->user() : $user;
		$status = 'pending';
		$data = Api::company($request->company);
		if ($data['code'] == 200) {
			$status = $data['data'];
		}

		$user->update([
			'company_name' => $request->company,
			'company_status' => $status,
		]);

		return ['status' => $status];
	}

	public function status(User $user)
	{
		$user = empty($user) ? auth()->user() : $user;
		return ['status' => $user->company_status];
	}

	public function push(Request $request)
    {
    	$data = $request->json()->all();

    	Log::info('Company Push', ['data' => $data]);
    }

    public function saveSettings(Request $request)
    {
    	$data = $request->all();
    	auth()->user()->update([
			'company_name' => $data['company_name'],
			'additional_phones' => implode(',', $data['additional_phones']),
		]);

		return $this->message('Settings was successfully saved.', 'success');
    }
}
