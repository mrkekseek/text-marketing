<?php

namespace App\Http\Middleware;

use Closure;

class Timezone
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
        $diffInHours = \Carbon\Carbon::createFromTimestamp($request->header('X-Local-Time'))->timestamp / 60 - 5;
        config(['app.offset' => $diffInHours]);
        
        if (auth()->check() && empty(auth()->user()->admins_id)) {
            auth()->user()->update([
                'offset' => $diffInHours,
            ]);
        }
        
        return $next($request);
    }
}
