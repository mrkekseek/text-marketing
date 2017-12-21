<?php

namespace App\Http\Controllers;

use App\Seance;
use App\Survey;
use App\User;
use App\Question;
use App\SocialUrl;
use Bitly;
use App\Http\Requests\SeancesCreateRequest;
use App\Http\Services\SurveysService;
use Illuminate\Http\Request;
use App\Jobs\SendEmail;
use Carbon\Carbon;

class SeancesController extends Controller
{
	public function info($id = false)
	{
		return Seance::find($id);
	}

    public function create(SeancesCreateRequest $request)
    {
        $this->surveySave($request);

        if ( ! empty($request->text)) {
            if (auth()->user()->company_name == $request->company) {
                if (auth()->user()->company_status != 'verified') {
                    return $this->message('Company Name must be verified');
                }
            } else {
                return $this->message('This Company Name isn\'t verified');
            }
        }
            

    	foreach ($request->clients as $client) {
            $code = $this->code($request->date, $request->time);
            $data = [
                'client_id' => $client['id'],
                'survey_id' => auth()->user()->surveys()->first()->id,
                'code' => $code,
                'url' => $this->url($code),
                'date' => $this->getDate($request->schedule ,$request->date, $request->time),
                'type' => $this->getType($request->text, $request->email),
            ];
            $seance = auth()->user()->seances()->create($data);

            if ( ! empty($request->text)) {
                // Send text
            }

            if ( ! empty($request->email)) {
                $this->sendEmail($client, $seance, $request->survey, $data['date']);
            }
    	}
        
    	return $this->message('Review was successfully saved', 'success');
    }

    public function sendEmail($client, $seance, $survey, $date)
    {
        $now = Carbon::now();
        $delay = $now->diffInminutes($date);
        die;
        $job = (new SendEmail($client, $seance, $survey))->delay($delay)->onQueue('emails');
        $this->dispatch($job);
    }

    private function surveySave($request)
    {
        $data = [
            'text' => $request->survey['text'],
            'sender' => $request->survey['sender'],
            'subject' => $request->survey['subject'],
            'email' => $request->survey['email'],
        ];
        SurveysService::save($data);
    }

    public function getSeance($param)
    {
        $seance = Seance::where('code', $param)->first();
        $seance['user'] = User::where('id', $seance['users_id'])->first();
        $seance['user']['urls'] = SocialUrl::where('users_id', $seance['users_id'])->get();
        $seance['survey'] = Survey::where('id', $seance['surveys_id'])->first();
        $seance['survey']['questions'] = Question::all();
        return view('survey')->with(['seance' => $seance]);
    }

    public function socialSave($id = false, $post = [])
    {
        $seance = Seance::find($id);
        $seance->update(['social_tap' => $post['name']]);
    }

    public function getDate($schedule, $date, $time)
    {
        if ( ! empty($schedule)) {
            $date = strtotime($date);
            $time = strtotime($time);
            return mktime(date('H', $time), date('i', $time), 0, date('m', $date), date('d', $date), date('Y', $date));
        }
    	return Carbon::now();
    }

    public function getType($text, $email)
    {
        $type = [];
        if ( ! empty($text)) {
            $type[] = 'text';
        }

        if ( ! empty($email)) {
            $type[] = 'email';
        }

        return implode(',', $type);
    }

    public function code($date, $time)
	{
		return md5(mt_rand(100, 999).time().$date.$time);
	}

	public function url($code)
	{
		return Bitly::getUrl(config('app.url').'/survey/'.$code);
	}
}
