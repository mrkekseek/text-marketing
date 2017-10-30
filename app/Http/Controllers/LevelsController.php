<?php

namespace App\Http\Controllers;

use App\Level;
use Illuminate\Http\Request;

class LevelsController extends Controller
{
    public function get($post = [])
	{
		return Level::all();
	}
}
