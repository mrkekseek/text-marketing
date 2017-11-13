<?php

namespace App\Http\Controllers;

use App\User;
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

    public function signout($post = [])
    {
        Auth::logout();
        return $this->message(__("You are out"), 'success');
    }
}
