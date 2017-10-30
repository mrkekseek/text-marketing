<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function info($id = false)
    {
    	echo 'sasdasda';
    }

    public function get($post = [])
	{
		return User::all();
	}
}
