<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResponseController extends Controller
{
    public function company(Request $request)
    {
    	$data = $request->all();

    	Log::info('Company Push', ['data' => $data]);
    }
}
