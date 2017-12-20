<?php

namespace App\Http\Controllers;

use App\Plan;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function all()
	{
		$plans = Plan::withCount('users')->get();
		if ( ! count($plans)) {
			$this->sync();
			$plans = Plan::withCount('users')->get();
		}
		
		return $plans;
	}

	public function sync()
	{
		$stripe = new Stripe(config('services.stripe.secret'));
		$plans = $stripe->plans()->all();
		foreach ($plans['data'] as $row) {
			if (strpos($row['id'], strtolower(config('app.name'))) !== false) {
				$plan = new Plan();
				$plan->plans_id = $row['id'];
				$plan->name = $row['name'];
				$plan->amount = $row['amount'] / 100;
				$plan->interval = $row['interval'];
				$plan->reviews = ! empty($row['metadata']['reviews']) ? $row['metadata']['reviews'] : 0;
				$plan->tms = ! empty($row['metadata']['tms']) ? $row['metadata']['tms'] : 0;
				$plan->emails = ! empty($row['metadata']['emails']) ? $row['metadata']['emails'] : 0;
				$plan->trial = ! empty($row['metadata']['trial']) ? $row['metadata']['trial'] : 0;
				$plan->save();
			}
		}
	}

	public function save($id = false, $post = [])
	{
		$plan = Plan::firstOrNew(['id' => empty($id) ? 0 : $id]);
		$plan->plans_id = empty($plan->plans_id) ? $this->plansId($id, $post) : $plan->plans_id;
		$plan->name = $post['name'];
		$plan->amount = $post['amount'];
		$plan->interval = $post['interval'];
		$plan->reviews = $post['reviews'];
		$plan->tms = $post['tms'];
		$plan->emails = $post['emails'];
		$plan->trial = $post['trial'];
		$plan->save();

		$this->saveOnStripe($plan, $id);

		return $this->message(__('Team was successfully saved'), 'success');
	}

	public function plansId($id = false, $post = [])
	{
		$check = false;
		$plans_id = strtolower(str_replace([' ', '.', ',', '/', '*', '_'], '-', $post['name']).'-'.config('app.name'));
		while ( ! $check) {
			$count = Plan::where('plans_id', $plans_id)->where('id', '<>', $id)->count();
			if (empty($count)) {
				$check = true;
			} else {
				$plans_id = '1'.$plans_id;
			}
		}
		return $plans_id;
	}

	public function saveOnStripe($plan, $update = false)
	{
		$stripe = new Stripe(config('services.stripe.secret'));
		if (empty($update)) {
			$stripe->plans()->create([
				'id' => $plan->plans_id,
				'name' => $plan->name,
				'amount' => $plan->amount,
				'currency' => 'USD',
				'interval' => $plan->interval,
				'metadata' => [
					'reviews' => $plan->reviews,
					'tms' => $plan->tms,
					'emails' => $plan->emails,
					'trial' => $plan->trial
				]
			]);
		} else {
			$stripe->plans()->update($plan->plans_id, [
				'name' => $plan->name,
				'metadata' => [
					'reviews' => $plan->reviews,
					'tms' => $plan->tms,
					'emails' => $plan->emails,
					'trial' => $plan->trial
				]
			]);
		}
	}

	public function remove($id = false)
	{
		$plan = Plan::withCount('users')->where('id', $id)->first();
		if (empty($plan->users_count)) {
			$this->removeOnStripe($plan->plans_id);
			Plan::destroy($id);
			return $this->message(__('Plan was successfully removed'), 'success');
		} else {
			return $this->message(__('You can\'t remove a plan with active users on it. First change a plan for those users or remove them'));
		}
	}

	public function removeOnStripe($plans_id)
	{
		$stripe = new Stripe(config('services.stripe.secret'));
		$stripe->plans()->delete($plans_id);
	}
}
