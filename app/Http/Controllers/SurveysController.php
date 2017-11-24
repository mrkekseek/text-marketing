<?php

namespace App\Http\Controllers;

use App\Survey;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveysController extends Controller
{
    public function all()
    {
    	$survey = User::find(Auth::user()->id)->surveys->first();
    	if (empty($survey)) {
    		$survey = Survey::where('users_id', 0)->first();
    	}
    	return $survey;
    }
}
