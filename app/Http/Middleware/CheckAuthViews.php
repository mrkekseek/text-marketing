<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
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
           /* if (auth()->user()->plans_id != 'none' || auth()->user()->plans_id != 'free-contractortexter') {
                if (auth()->user()->onTrial()) {
                    if ( ! empty(auth()->user()->stripe_id)) {
                        if ( ! auth()->user()->subscribed('main')) {
                            return response(view('stripe'));
                        }
                    } else {
                        return response(view('stripe'));
                    }
                }
            }*/
            return $next($request);
        }
    }
}
