<?php

namespace App\Http\Controllers;

use App\Survey;
use App\User;
use App\Http\Services\SurveysService;
use Illuminate\Http\Request;

class SurveysController extends Controller
{
    public function info(User $user = null)
    {
        $user = empty($user) ? auth()->user() : $user;
    	$survey = $user->surveys;
    	if (empty($survey)) {
    		$survey = Survey::findDefault();
        }
        $survey->sender = str_replace('[$myFirstName]', $user->firstname, $survey->sender);
    	return $survey;
    }

    public function text(Request $request, User $user = null)
    {
        $data = $request->only(['text', 'sender', 'subject', 'email']);
        $data['alerts_emails'] = empty($data['alerts_emails']) ? '' : $data['alerts_emails'];
        $data['alerts_stars'] = empty($data['alerts_stars']) ? 0 : $data['alerts_stars'];
        $data['alerts_often'] = empty($data['alerts_often']) ? 0 : $data['alerts_often'];
        $data['text'] = ! empty($data['text']) ? $data['text'] : '';
        $data['sender'] = ! empty($data['sender']) ? $data['sender'] : '';
        $data['subject'] = ! empty($data['subject']) ? $data['subject'] : '';
        $data['email'] = ! empty($data['email']) ? $data['email'] : '';
        SurveysService::save($data, $user);
        return $this->message('SMS Text was saved', 'success');
    }

    public function email(Request $request, User $user = null)
    {
        $data = $request->only(['text', 'sender', 'subject', 'email']);
        $data['alerts_emails'] = empty($data['alerts_emails']) ? '' : $data['alerts_emails'];
        $data['alerts_stars'] = empty($data['alerts_stars']) ? 0 : $data['alerts_stars'];
        $data['alerts_often'] = empty($data['alerts_often']) ? 0 : $data['alerts_often'];
        $data['text'] = ! empty($data['text']) ? $data['text'] : '';
        $data['sender'] = ! empty($data['sender']) ? $data['sender'] : '';
        $data['subject'] = ! empty($data['subject']) ? $data['subject'] : '';
        $data['email'] = ! empty($data['email']) ? $data['email'] : '';
        SurveysService::save($data, $user);
        return $this->message('Email and Subject were saved', 'success');
    }

    public function save(Request $request)
    {
        $data = $request->only(['text', 'sender', 'subject', 'email', 'alerts_emails', 'alerts_stars', 'alerts_often']);
        $data['alerts_emails'] = empty($data['alerts_emails']) ? '' : $data['alerts_emails'];
        $data['alerts_stars'] = empty($data['alerts_stars']) ? 0 : $data['alerts_stars'];
        $data['alerts_often'] = empty($data['alerts_often']) ? 0 : $data['alerts_often'];
        $data['text'] = ! empty($data['text']) ? $data['text'] : '';
        $data['sender'] = ! empty($data['sender']) ? $data['sender'] : '';
        $data['subject'] = ! empty($data['subject']) ? $data['subject'] : '';
        $data['email'] = ! empty($data['email']) ? $data['email'] : '';
        SurveysService::save($data, auth()->user());
        return $this->message('Alert settings was successfully saved', 'success');
    }
}
