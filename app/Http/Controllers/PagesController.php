<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function defaultPage($post = [])
    {
    	return auth()->user()->type == 1 ? 'users.list' : 'homeadvisor.index';
    }
}
