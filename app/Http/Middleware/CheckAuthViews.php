<?php

namespace App\Http\Middleware;

use Closure;
use App\Plan;
use Carbon\Carbon;
use Cartalyst\Stripe\Stripe;
use Illuminate\Support\Facades\Auth;

class CheckAuthViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( ! $request->is('view/auth/*') && ! Auth::check()) {
            return response(view('auth.signin'));
        } else {
            $user = auth()->user();
            $plans_id = $user->plans_id;
            $plan = Plan::where('plans_id', $plans_id)->first();
            if ( ! empty($plan) && ! $user->subscribed($plan->name) && Carbon::parse($user->created_at)->timestamp > 1526630434 || $user->allow_access && Carbon::parse($user->created_at)->timestamp > 1526630434) {
                return response(view('plans.info'));
            }
        }

        return $next($request);
    }
}
