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

    public function save(Request $request, $id = false)
    {
    	$survey = Survey::firstOrNew(['users_id' => auth()->user()->id]);
        $survey->users_id = auth()->user()->id;
        $survey->text = $request['text'];
        $survey->email = $request['email'];
        $survey->subject = $request['subject'];
        $survey->sender = $request['sender'];
        $survey->alerts_often = $request['alerts_often'];
        $survey->alerts_stars = $request['alerts_stars'];
        $survey->alerts_emails = ! empty($request['alerts_emails']) ? $request['alerts_emails'] : '';
        $survey->save();

        $this->message(__('Settings was successfully saved'), 'success');
    }
}
