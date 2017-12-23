<?php

namespace App\Http\Controllers;

use App\User;
use App\Libraries\Api;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\UsersService;
use App\Http\Services\LinksService;
use App\Http\Requests\UsersCreateRequest;
use App\Http\Requests\UsersPasswordRequest;
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

	public function create(UsersCreateRequest $request)
	{
		$data = $request->only(['plans_id', 'firstname', 'lastname', 'email', 'password', 'view_phone']);
		$data['type'] = 2;
		$data['teams_leader'] = true;
		$data['active'] = true;
		$data['password'] = UsersService::password($data['password']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data['teams_id'] = UsersService::createTeam($data);
		$data = array_filter($data, 'strlen');

		$user = User::create($data);
		$user->defaultUrls();
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

	public function profile(UsersCreateRequest $request)
	{
		$data = $request->only(['firstname', 'lastname', 'email', 'view_phone']);
		$data['phone'] = UsersService::phoneToNumber($data);
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

	public function company(Request $request)
	{
		$status = 'pending';
		$data = Api::company($request->company);
		if ($data['code'] == 200) {
			$status = $data['data'];
		}

		auth()->user()->update([
			'company_name' => $request->company,
			'company_status' => $status,
		]);

		return ['status' => $status];
	}

	public function status()
	{
		return ['status' => auth()->user()->company_status];
	}

	public function push(Request $request)
    {
    	$data = $request->json()->all();

    	Log::info('Company Push', ['data' => $data]);
    }
}
