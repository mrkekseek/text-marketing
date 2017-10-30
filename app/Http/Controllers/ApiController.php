<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __invoke($unit, $method)
    {
    	if ($unit == 'auth' || $unit != 'auth' && auth()->check())
    	{
	    	$post = request()->all();
	    	unset($post['_method'], $post['_token'], $post['pivot']);

		    $controller = app()->make('\App\Http\Controllers\\'.ucfirst($unit).'Controller');    
		    $response = $controller->callAction($method, ['post' => $post]);                    
            return json_encode($response, JSON_NUMERIC_CHECK);
    	}
    }
}
