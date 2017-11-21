<?php

namespace App\Http\Controllers;

use App\Plan;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function all()
	{
		$stripe = new Stripe(config('services.stripe.secret'));
		$plans = $stripe->plans()->all();
		foreach ($plans['data'] as $row) {
			if (strpos($row['id'], 'contractortexter')) {
				$plan = Plan::firstOrNew(['plans_id' => $row['id']]);
				$plan->plans_id = $row['id'];
				$plan->plans_name = $row['name'];
				$plan->save();
			}
		}
		return Plan::all();
	}

	public function remove($id)
	{
		Plan::destroy($id);
		return $this->message(__('Plan was successfully removed'), 'success');
	}
}
