<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Seance;
use App\Question;
use App\SocialUrl;
use App\User;
use App\Survey;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Services\SurveysService;
use App\Jobs\SendAlertNow;
use App\Jobs\SendAlertDelay;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertDelaySend;

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
            'completed' => Carbon::now()->subHours($seance->review->user->offset),
            'show' => $show,
            'alert' => false,
        ]);

        $this->alert($seance, $seance->answers()->where('question_id', 1)->first()->value);
    }

    public function email($id, $value)
    {
        $seance = Seance::where('id', $id)->with(['review.survey', 'review.user.urls'])->first();
        if ($value == 5) {
            $seance->update([
                'completed' => Carbon::now()->subHours($seance->review->user->offset),
                'show' => true,
                'alert' => false,
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

        $this->alert($seance, $value);
        
        return view('survey')->with(compact('seance', 'questions'));
    }

    public function text($code)
    {
        $seance = Seance::where('code', $code)->with(['review.survey', 'review.user.urls'])->first();
        $questions = Question::all();

        return view('survey')->with(compact('seance', 'questions'));
    }

    public function alert($seance, $value)
    {
        $survey = $seance->review->survey;
        if ($survey->alerts_stars >= $value) {
            if (empty($survey->alerts_often)) {
                $seance->update([
                    'alert' => true,
                ]);
                SendAlertNow::dispatch($seance->review->user, $value)->onQueue('emails');
            } else {
                $delay = SurveysService::alertsDelay($survey->alerts_often);
                SendAlertDelay::dispatch($seance->review->user)->onQueue('emails')->delay($delay);
            }
        }
    }
}
