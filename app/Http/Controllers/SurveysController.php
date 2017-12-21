<?php

namespace App\Http\Controllers;

use App\Survey;
use App\User;
use App\Http\Services\SurveysService;
use Illuminate\Http\Request;

class SurveysController extends Controller
{
    public function info()
    {
    	$survey = auth()->user()->surveys;
    	if (empty($survey)) {
    		$survey = Survey::findDefault();
        }
        $survey->sender = str_replace('[$myFirstName]', auth()->user()->firstname, $survey->sender);
    	return $survey;
    }

    public function text(Request $request)
    {
        $data = $request->only(['text', 'sender', 'subject', 'email']);
        SurveysService::save($data);
        return $this->message('SMS Text was saved', 'success');
    }

    public function email(Request $request)
    {
        $data = $request->only(['text', 'sender', 'subject', 'email']);
        SurveysService::save($data);
        return $this->message('Email and Subject were saved', 'success');
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
