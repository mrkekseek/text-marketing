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

        $canSave = true;
        if ( ! empty($request->text)) {
            $canSave *= $this->textValidate($request);
        }

        if ( ! empty($request->email)) {
            $canSave *= $this->emailValidate($request);
        }

        if (empty($canSave)) {
            return false;
        }

        $text = trim(auth()->user()->surveys()->first()->text);

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
            
            if ( ! empty($request->email)) {
                $this->sendEmail($client, $seance, $request->survey, $data['date']);
            }
        }

        if ( ! empty($request->text)) {
            $this->sendText($request, $text);
        }

        return $this->message('Review was successfully saved', 'success');
    }

    private function textValidate($request)
    {
        if ( ! ApiValidate::companyExists($request->company)) {
            return $this->message('This Company Name isn\'t verified');
        }

        if ( ! ApiValidate::companyVerified($request->company)) {
            return $this->message('Company Name must be verified');
        }

        $text = trim(auth()->user()->surveys()->first()->text);
        if ( ! ApiValidate::messageSymbols($text)) {
            return $this->message('SMS Text contains forbidden characters');
        }

        $clients = $this->getClients($request, $text);
        $length = true;
        $phones = true;
        foreach ($clients as $client) {
            $message = $text;

            if ( ! empty($client['link'])) {
                $message = str_replace('[$Link]', $client['firstname'], $message);
            }

            if ( ! empty($client['firstname'])) {
                $message = str_replace('[$FirstName]', $client['firstname'], $message);
            }

            if ( ! empty($client['lastname'])) {
                $message = str_replace('[$LastName]', $client['lastname'], $message);
            }

            if ( ! ApiValidate::messageLength($message, $request->company)) {
                $length = false;
            }

            if ( ! ApiValidate::phoneFormat($client['phone'])) {
                $phones = false;
            }
        }

        if (empty($length)) {
            return $this->message('SMS Text is too long');
        }

        if (empty($phones)) {
            return $this->message('Some client\'s phone numbers have wrong format');
        }

        if (ApiValidate::underBlocking()) {
            return $this->message('You can\'t send texts before 9 AM. You can try to use Schedule Send');
        }

        return true;
    }

    private function emailValidate($request)
    {
        return true;
    }

    private function getClients($request, $text)
    {
        $clients = [];
        foreach ($request->clients as $client) {
            $row = [
                'phone' => $client['phone'],
                'link' => $data['url']
            ];

            if (strpos($text, '[$FirstName]') !== false) {
                $row['firstname'] = $client['firstname'];
            }

            if (strpos($text, '[$LastName]') !== false) {
                $row['lastname'] = $client['lastname'];
            }

            $clients[] = $row;
        }
    }

    public function sendText($request, $text)
    {
        $response = Api::survey($this->getClients($request, $text), $text, auth()->user()->company_name);
        if ($response['code'] == 200) {
            // save Receiveers
        }
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
