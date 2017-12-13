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
    public function save($id = false, $post = [])
    {
    	if ( ! empty($post['answers'])) {
    		foreach ($post['answers'] as $row) {
    			$answer = Answer::firstOrNew(['seances_id' => $row['seances_id'], 'questions_id' => $row['questions_id']]);
                $answer->users_id = $row['users_id'];
                $answer->clients_id = $row['clients_id'];
                $answer->seances_id = $row['seances_id'];
                $answer->surveys_id = $row['surveys_id'];
                $answer->questions_id = $row['questions_id'];
                $answer->questions_type = $row['questions_type'];
                $answer->questions_text = $row['questions_text'];
                $answer->value = $row['value'];
    			$answer->save();
			}
			$seance = Seance::where('id', $post['seance']['id']);
			$seance->update([
				'completed' => 1,
				'social_show' => ! empty($post['seance']['show_reviews']) ? $post['seance']['show_reviews'] : 0
			]);

            $survey = Survey::where('id', $answer->surveys_id)->first();

            if ( ! empty($survey->alerts_emails)) {
                $answer = Answer::where('seances_id', $post['seance']['id'])->where('questions_type', 'star')->first();
                $this->sendAlerts($survey, $answer->value);
            }
    	}
    }

    public function saveEmail($id, $value)
    {
        $question = Question::find(1);
        $seance = Seance::find($id);
        if ($value == 5) {
            $seance->update([
                'completed' => 1
            ]);
            $seance['show_reviews'] = true;
        }

        $answer = new Answer();
        $answer = Answer::firstOrNew(['seances_id' => $id, 'questions_id' => 1]);
        $answer->users_id = $seance->users_id;
        $answer->clients_id = $seance->clients_id;
        $answer->seances_id = $id;
        $answer->surveys_id = $seance->surveys_id;
        $answer->questions_id = 1;
        $answer->questions_type = 'star';
        $answer->questions_text = $question->text;
        $answer->save();

        $seance['user'] = User::where('id', $seance['users_id'])->first();
        $seance['user']['urls'] = SocialUrl::where('users_id', $seance['users_id'])->get();
        $seance['survey'] = Survey::where('id', $seance['surveys_id'])->first();
        
        if ($value != 5) {
            $seance['bed_answers'] = true;
            $seance['survey']['questions'] = Question::where('id', 2)->get();
            $seance['class'] = 'col-sm-4 col-sm-offset-4';
        }
        if ( ! empty($seance['survey']->alerts_emails)) {
            $this->sendAlerts($seance['survey'], $value);
        }
        
        return view('survey')->with(['seance' => $seance]);
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
