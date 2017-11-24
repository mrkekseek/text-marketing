<?php

namespace App\Http\Controllers;

use App\Seance;
use App\Survey;
use App\Question;
use Bitly;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeancesController extends Controller
{
	public function info($id = false)
	{
		return Seance::find($id);
	}

    public function save($id = false, $post = [])
    {
        $survey = $this->surveySave($post);

    	foreach ($post['clients'] as $row) {
    		$seance = new Seance;
    		$seance->users_id = Auth::user()->id;
    		$seance->clients_id = $row['id'];
    		$seance->surveys_id = $survey->id;
    		$seance->code = $this->codeGenerate($post);
    		$seance->url = $this->urlGenerate($seance->code);
    		$seance->date = $this->getDate($post['date'], $post['time']);
    		$seance->completed = 0;
    		$seance->type = ! empty($post['type']) ? implode(',', $post['type']) : '';
    		$seance->save();
    	}
    	$this->message(__('Seances was successfully saved'), 'success');
    }

    public function surveySave($post)
    {
        $survey = Survey::firstOrNew(['users_id' => Auth::user()->id]);
        $survey->users_id = Auth::user()->id;
        $survey->title = $post['survey']['title'];
        $survey->text = $post['survey']['text'];
        $survey->email_text = $post['survey']['email_text'];
        $survey->email_subject = $post['survey']['email_subject'];
        $survey->email_sender = $post['survey']['email_sender'];
        $survey->save();
        return $survey;
    }

    public function getSeance($param)
    {
        $seance = Seance::where('code', $param)->first();
        $seance['survey'] = Survey::where('id', $seance['surveys_id'])->first();
        $seance['survey']['questions'] = Question::all();
        return view('survey')->with(['seance' => $seance]);
    }

    public function getDate($date, $time)
    {
    	$date = strtotime($date);
    	$time = strtotime($time);
    	return mktime(date('H', $time), date('i', $time), 0, date('m', $date), date('d', $date), date('Y', $date));
    }

    public function codeGenerate($post)
	{
		return md5(mt_rand(100, 999).time().$post['date'].$post['time']);
	}

	public function urlGenerate($code)
	{
		return Bitly::getUrl(config('app.url').'/survey/'.$code);
	}
}
