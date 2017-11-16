<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function all()
	{
		return Plan::all();
	}
}
