<?php

namespace App\Http\Controllers;

use App\Plan;
use App\User;
use App\FreePlan;
use App\Homeadvisor;
use Carbon\Carbon;
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
				$plan->name = $row['nickname'];
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

	public function savePlan(Request $request)
	{
		$plans_id = $this->plansId($request);

		if (! empty($plans_id)) {
			$plan = new Plan();
			$plan->plans_id = $plans_id;
			$plan->name = $request['name'];
			$plan->amount = $request['amount'];
			$plan->interval = $request['interval'];
			$plan->reviews = $request['reviews'];
			$plan->tms = $request['tms'];
			$plan->emails = $request['emails'];
			$plan->trial = $request['trial'];
			$plan->save();

			$this->saveOnStripe($plan);

			return $this->message(__('Plan was successfully saved'), 'success');
		}
	}

	public function updatePlan(Request $request, $id)
	{
		$plan = Plan::firstOrNew(['id' => empty($id) ? 0 : $id]);
		$plan->plans_id = $this->plansId($request);
		$plan->name = $request['name'];
		$plan->amount = $request['amount'];
		$plan->interval = $request['interval'];
		$plan->reviews = $request['reviews'];
		$plan->tms = $request['tms'];
		$plan->emails = $request['emails'];
		$plan->trial = $request['trial'];
		$plan->save();

		$this->saveOnStripe($plan, true);

		return $this->message(__('Plan was successfully saved'), 'success');
	}

	public function plansId(Request $request)
	{
		$check = false;
		$plans_id = strtolower(str_replace([' ', '.', ',', '/', '*', '_'], '-', $request['name']).'-'.config('app.name'));

		return $plans_id;
	}

	public function saveOnStripe($plan, $update = false)
	{
		$stripe = new Stripe(config('services.stripe.secret'));
		if (empty($update)) {
			$stripe->plans()->create([
				'id' => $plan->plans_id,
				'nickname' => $plan->name,
				'amount' => $plan->amount,
				'currency' => 'USD',
				'interval' => $plan->interval,
				'product' => config('services.stripe.product'),
				'metadata' => [
					'reviews' => $plan->reviews,
					'tms' => $plan->tms,
					'emails' => $plan->emails,
					'trial' => $plan->trial
				]
			]);
		} else {
			$stripe->plans()->update($plan->plans_id, [
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

	public function getPlanInfo()
	{
		$user = auth()->user();
		$subscription = $user->subscriptions()->first();
		$plan = Plan::where('plans_id', $user->plans_id)->first();

		if ( ! empty($subscription) && $user->subscribed($subscription->name)) {
			$data = [
				'stripe_id' => $subscription->stripe_id,
				'card_brand' => $user->card_brand,
				'card_last_four' => $user->card_last_four,
				'plan_name' => $subscription->name,
				'status' => 'Active',
			];

			return $data;
		} else {
			$data = [
				'plan_name' => $plan->name,
			];

			return $data;
		}
	}

	public function subscribe(Request $request)
	{
		$user = auth()->user();

		if ( ! empty($request['rep']['value'])) {
			$ha = Homeadvisor::where('users_id', $user->id)->first();
			$ha->update([
				'rep' => $request['rep']['value']
			]);
		}

		$stripe = new Stripe(config('services.stripe.secret'));
		$check_plan = $stripe->plans()->find(auth()->user()->plans_id);
		$plan = Plan::where('plans_id', $user->plans_id)->first();
		if ( ! $user->subscribed($plan->name)) {
			if (empty($check_plan['metadata']['trial'])) {
				$user->newSubscription($plan->name, $user->plans_id)->create($request['token']);
			} else {
				$user->newSubscription($plan->name, $user->plans_id)->trialDays($check_plan['metadata']['trial'])->create($request['token']);
			}
			return $this->message('Your have subscribed', 'success');
		}
	}

	public function cancelSubscription(Request $request, User $user)
	{
		if (empty($user->id))
		{
			$user = auth()->user();
			if ($user->subscribed($request['plan_name'])) {
				$plan = Plan::where('plans_id', $user->plans_id)->first();
				$subscription = $user->subscription($request['plan_name']);
				$subscription->swap('canceled-contractortexter');
				$subscription->name = 'Canceled';
				$subscription->save();
				$user->update([
					'plans_id' => 'canceled-contractortexter',
					'paused_plans_id' => $user->plans_id,
					'cancellation_reason' => ! empty($request['reason']) ? $request['reason'] : '',
				]);

				auth()->logout();
			}
		} else {
			$plan = Plan::where('plans_id', $user->plans_id)->first();
			if ($user->subscribed($plan->name)) {
				$subscription = $user->subscription($plan->name);
				$subscription->swap('canceled-contractortexter');
				$subscription->name = 'Canceled';
				$subscription->save();
				$user->update([
					'plans_id' => 'canceled-contractortexter',
					'paused_plans_id' => $plan->plans_id,
					'cancellation_reason' => 'Canceled by admin',
				]);
			} else {
				$user->update([
					'plans_id' => 'canceled-contractortexter',
					'paused_plans_id' => $plan->plans_id,
					'cancellation_reason' => 'Canceled by admin',
				]);
			}
		}
		return $this->message('You have canceled your subscription', 'success');
	}

	public function makeFreePlan(Request $request, User $user)
	{
		if (empty($user->id))
		{
			$user = auth()->user();
			$subscription = $user->subscription($request['plan_name']);
			$plan_id = $subscription->stripe_plan;
			$subscription->swap('free-contractortexter');
			$subscription->name = 'Free';
			$subscription->save();
			$user->update([
				'plans_id' => 'free-contractortexter',
				'paused_plans_id' => $plan_id,
			]);
			$free_plan = FreePlan::create([
				'users_id' => $user->id,
				'started_at' => Carbon::now(),
				'ends_at' => Carbon::now()->addMonth()
			]);
		} else {
			$plan = Plan::where('plans_id', $user->plans_id)->first();
			$subscription = $user->subscription($plan->name);
			$subscription->swap('free-contractortexter');
			$subscription->name = 'Free';
			$subscription->save();
			$user->update([
				'plans_id' => 'free-contractortexter',
				'paused_plans_id' => $plan->plans_id == 'canceled-contractortexter' ? $user->paused_plans_id : $plan->plans_id,
				'cancellation_reason' => '',
			]);
			$free_plan = FreePlan::create([
				'users_id' => $user->id,
				'started_at' => Carbon::now(),
				'ends_at' => Carbon::now()->addMonth()
			]);
		}
		return $this->message('You have subscribed to Free plan', 'success');
	}

	public function reactivatePlan(Request $request, User $user)
	{
		if (empty($user->id)) {
			$user = auth()->user();
			$subscription = $user->subscription($request['plan_name']);
			$plan = Plan::where('plans_id', $user->paused_plans_id)->first();
			$subscription->swap($user->paused_plans_id);
			$subscription->name = $plan->name;
			$subscription->save();
			$user->update([
				'plans_id' => $user->paused_plans_id,
				'paused_plans_id' => '',
			]);
			$free_plan = FreePlan::where('users_id', $user->id)->get();
			if ( ! empty($free_plan)) {
				foreach($free_plan as $item) {
					$item->delete();
				}
			}
		} else {
			$plan = Plan::where('plans_id', $user->plans_id)->first();
			$subscription = $user->subscription($plan->name);
			$paused_plan = Plan::where('plans_id', $user->paused_plans_id)->first();
			if ($subscription) {
				$subscription->swap($user->paused_plans_id);
				$subscription->name = $paused_plan->name;
				$subscription->save();
				$user->update([
					'plans_id' => $user->paused_plans_id,
					'paused_plans_id' => '',
				]);
				$free_plan = FreePlan::where('users_id', $user->id)->get();
				if ( ! empty($free_plan)) {
					foreach($free_plan as $item) {
						$item->delete();
					}
				}
			} else {
				$user->update([
					'plans_id' => $user->paused_plans_id,
					'paused_plans_id' => '',
				]);
			}

		}
		return $this->message('You have reactivate your plan', 'success');
	}

	public function assignPlanToUser(Request $request, User $user)
	{
		$plan = Plan::where('id', $request['plans_id'])->first();
		$user->update([
			'plans_id' => $plan->plans_id,
		]);
		return $this->message('Plan was successfully assigned', 'success');
	}

	public function updateCard(Request $request)
	{
		$user = auth()->user();
		$user->updateCard($request['token']);
		return $this->message('Card details was updated', 'success');
	}
}
