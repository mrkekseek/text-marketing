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
        /*$this->user = auth()->user();
        $alerts = [];
        foreach ($this->user->reviews as $review) {
            $seances = $review->seances()->alerts($this->user->surveys->alerts_stars, $this->user->surveys->alerts_often)->with([
                'answers' => function($q) {
                    $q->where('question_id', 1);
                }
            ])->get();

            foreach ($seances as $seance) {
                $alerts[] = $seance;
            }
        }
        print_r($alerts);
        exit;*/

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

    public function save(Request $request)
    {
        $data = $request->only(['text', 'sender', 'subject', 'email', 'alerts_emails', 'alerts_stars', 'alerts_often']);
        $data['alerts_emails'] = empty($data['alerts_emails']) ? '' : $data['alerts_emails'];
        SurveysService::save($data);
        return $this->message('Alert settings was successfully saved', 'success');
    }
}
