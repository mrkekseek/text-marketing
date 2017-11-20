<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\ActivateUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function info($id = false)
    {
    	echo 'sasdasda';
    }

    public function all()
	{
		return User::where('type', '!=', 1)->get();
	}

	public function save($id = false, $post = [])
	{
		$validator = $this->validate(request(), [
            'email' => 'required|email|unique:users,email'.(empty($id) ? '' : ','.$id),
            'teams_id' => 'required',
            'firstname' => 'required',
            'password' => 'required_without:id',
        ]);

        if ( ! $validator->fails()) {

			$user = User::firstOrNew(['id' => empty($id) ? 0 : $id]);
			if ( ! empty($post['teams_leader'])) {
				$this->resetTeamsLeader($post['teams_id']);
			}
			$user->plans_id = $post['plans_code'];
			$user->teams_id = $post['teams_id'];
			$user->teams_leader = $post['teams_leader'];
			$user->type = 2;
			$user->email = strtolower($post['email']);
			$user->firstname = $post['firstname'];
			$user->lastname = $post['lastname'];
			$user->phone = $this->phoneToNumber($post['phone']);
			$user->active = $post['active'];

			if ( ! empty($post['password'])) {
				$user->password = bcrypt($post['password']);
			}

			$user->save();

			return $this->message(__('Teammate was successfully saved'), 'success');
		}

		return false;
	}

	public function remove($id)
	{
		User::destroy($id);
		return $this->message(__('User was successfully removed'), 'success');
	}

	public function teamsLeader($id = false, $post = [])
	{
		$user = User::find($id);
		if ( ! empty($post['_checked'])) {
			$this->resetTeamsLeader($user->teams_id);
		}
		$user->update(['teams_leader' => $post['_checked']]);
		return true;
	}

	public function resetTeamsLeader($teams_id)
	{
		DB::table('users')
			->where('teams_id', $teams_id)
			->update(['teams_leader' => false]);
	}

	public function active($id = false, $post = [])
	{
		$user = User::find($id);
		$user->update(['active' => $post['_checked']]);
		return true;
	}

	public function phoneToNumber($phone)
	{
		return str_replace(['-', '.', ' ', '(', ')'], '', $phone);
	}

	public function magic($id = false, $post = [])
	{
		$user = User::find($id);
		$user->admins_id = auth()->id();
		$user->save();

		auth()->login($user);
		
		return true;
	}
}
