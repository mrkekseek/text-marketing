<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function company(Request $request)
    {
    	$data = $request->all();

    	if( ! file_exists('logs'))
		{
			mkdir('logs', 0777);
		}
		file_put_contents('logs/logger.txt', date('[Y-m-d H:i:s] ').': '.print_r($data, true).PHP_EOL, FILE_APPEND | LOCK_EX);

    }
}
