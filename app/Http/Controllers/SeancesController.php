<?php

namespace App\Http\Controllers;

use App\Seance;
use App\Survey;
use App\User;
use App\Question;
use App\SocialUrl;
use App\Libraries\Api;
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

        $text = auth()->user()->surveys()->first()->text; 
        $clients = [];

    	foreach ($request->clients as $client) {
            $code = $this->code($request->time);
            $data = [
                'client_id' => $client['id'],
                'survey_id' => auth()->user()->surveys()->first()->id,
                'code' => $code,
                'url' => $this->url($code),
                'date' => $this->getDate($request->schedule, $request->time),
                'type' => $this->getType($request->text, $request->email),
            ];

            $seance = auth()->user()->seances()->create($data);

            if ( ! empty($request->text)) {
                $row = [
                    'phone' => $client['phone'],
                    'link' => $seance->url
                ];

                if (strpos($text, '[$FirstName]') !== false) {
                    $row['firstname'] = $client['firstname'];
                }

                if (strpos($text, '[$LastName]') !== false) {
                    $row['lastname'] = $client['lastname'];
                }

                $clients[] = $row;
            }

            if ( ! empty($request->email)) {
                $this->sendEmail($client, $seance, $request->survey, $data['date']);
            }
    	}

        if ( ! empty($request->text)) {
            $this->sendText($clients, $text);
        }
        
    	return $this->message('Review was successfully saved', 'success');
    }

    public function sendText($clients, $text)
    {
        $data = Api::survey($clients, $text, auth()->user()->company_name);
    }

    public function sendEmail($client, $seance, $survey, $date)
    {
        $delay = Carbon::now()->diffInSeconds($date);

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

    public function getDate($schedule, $time)
    {
        if ( ! empty($schedule)) {
            return Carbon::create($time['year'], $time['month'], $time['date'], $time['hours'], $time['minutes'], 0, config('app.timezone'));
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

    public function code($time)
	{
		return md5(mt_rand(100, 999).time().$time['hours'].$time['minutes']);
	}

	public function url($code)
	{
		return Bitly::getUrl(config('app.url').'/survey/'.$code);
	}
}
