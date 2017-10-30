<?php

namespace App\Http\Middleware;

use Closure;

class Messages
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
        $response = $next($request);
        if ($response->status() == 200)
        {
            $controller = app()->make('\App\Http\Controllers\Controller');
            $response = response()->json(['messages' => $controller::$errors, 'data' => $response->content()]);
        }

        return $response;
    }
}
