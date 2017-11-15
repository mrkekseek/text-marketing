<?php

namespace App\Http\Controllers;

use App\User;
use App\Team;
use App\Events\SignUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signin($post = [])
    {
        $validator = $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ( ! $validator->fails()) {
            $auth = [
            	'email' => $post['email'],
            	'password' => $post['password']
            ];

            if (Auth::validate($auth)) {
                $user = User::where('email', $auth['email'])->first();
                if ( ! empty($user->active)) {
                    Auth::attempt($auth);
                    $this->message(__('You are in'), 'success');
                    return true;
                } else {
                    $this->message(__('Your account is not active'));
                }
            } else {
                $this->message(__('Invalid username/password'));
            }
        }
        return false;
    }

    public function signup($post = [])
    {
        $validator = $this->validate(request(), [
            'email' => 'required|email|unique:users,email',
            'firstname' => 'required',
            'password' => 'required',
            'ha_rep' => 'required_if:plans_code,home-advisor'
        ]);

        if ( ! $validator->fails()) {
            $team = new Team();
            $team->name = $this->teamsName($post);
            $team->save();

            $user = new User();
            $user->password = bcrypt($post['password']);
            $user->plans_code = $post['plans_code'];
            $user->teams_id = $team->id;
            $user->teams_leader = 1;
            $user->type = 2;
            $user->email = strtolower($post['email']);
            $user->firstname = $post['firstname'];
            $user->lastname = ! empty($post['lastname']) ? $post['lastname'] : '';
            $user->active = 1;
            $user->save();

            event(new SignUp($user));

            return $this->message(__("You were successfully registered."), 'success');
        }
        return false;
    }

    public function teamsName($post) {
        $name = [$post['firstname']];

        if ( ! empty($post['lastname'])) {
           $name[] = $post['lastname'];
        }
        
        return implode(' ', $name);
    }

    public function signout($post = [])
    {
        Auth::logout();
        return $this->message(__("You are out"), 'success');
    }
}
