<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Seance;
use App\Question;
use App\SocialUrl;
use App\User;
use App\Survey;
use Illuminate\Http\Request;
use App\Jobs\SendAlert;

class AnswersController extends Controller
{
    public function save(Request $request, $id)
    {
        $show = false;
        $seance = Seance::find($id);
        foreach ($request->answers as $answer) {
            $seance->answers()->where('question_id', $answer['question_id'])->delete();
            $seance->answers()->create($answer);
            if ($answer['value'] == 5) {
                $show = true;
            }
        }

        $seance->update([
            'completed' => true,
            'show' => $show,
        ]);

        /*if ( ! empty($survey->alerts_emails)) {
            $answer = Answer::where('seances_id', $request['seance']['id'])->where('questions_type', 'star')->first();
            $this->sendAlerts($survey, $answer->value);
        }*/
    }

    public function email($id, $value)
    {
        $seance = Seance::where('id', $id)->with(['review.survey', 'review.user.urls'])->first();
        if ($value == 5) {
            $seance->update([
                'completed' => true,
                'show' => true,
            ]);
        }

        $seance->answers()->delete();
        $seance->answers()->create([
            'question_id' => Question::find(1)->id,
            'value' => $value,
        ]);
        
        $questions = 0;
        if ($value != 5) {
            $questions = Question::where('id', 2)->get();
        }

        /*if ( ! empty($seance['survey']->alerts_emails)) {
            $this->sendAlerts($seance['survey'], $value);
        }*/
        
        return view('survey')->with(compact('seance', 'questions'));
    }

    public function text($code)
    {
        $seance = Seance::where('code', $code)->with(['review.survey', 'review.user.urls'])->first();
        $questions = Question::all();

        return view('survey')->with(compact('seance', 'questions'));
    }

    public function sendAlerts($survey, $value)
    {
        if ($survey->alerts_stars >= $value) {
            $user = User::find($survey->users_id);
            $job = (new SendAlert($user, $value))->onQueue('emails');
            $this->dispatch($job);
        }
    }
}
