<?php

namespace App\Http\Controllers;

use App\Plan;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function all()
	{
		$stripe = new Stripe();
		$plans = $stripe->plans()->all();
		return Plan::all();
	}
}
