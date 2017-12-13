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

    public function save($id = false, $post = [])
    {
    	$survey = Survey::firstOrNew(['users_id' => auth()->user()->id]);
        $survey->users_id = auth()->user()->id;
        $survey->text = $post['text'];
        $survey->email = $post['email'];
        $survey->subject = $post['subject'];
        $survey->sender = $post['sender'];
        $survey->alerts_often = $post['alerts_often'];
        $survey->alerts_stars = $post['alerts_stars'];
        $survey->alerts_emails = ! empty($post['alerts_emails']) ? $post['alerts_emails'] : '';
        $survey->save();

        $this->message(__('Settings was successfully saved'), 'success');
    }
}
