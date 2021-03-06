<?php

namespace App\Http\Controllers;

use App\Seance;
use App\Review;
use App\Survey;
use App\User;
use App\Question;
use App\Libraries\ApiValidate;
use DivArt\ShortLink\Facades\ShortLink;
use App\Http\Requests\SeancesCreateRequest;
use App\Http\Services\SurveysService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendText;
use App\Jobs\SendEmail;
use Carbon\Carbon;

class SeancesController extends Controller
{
	public function info($id = false)
	{
		return Seance::find($id);
	}

    public function create(SeancesCreateRequest $request, User $user = null)
    {
        $user = empty($user) ? auth()->user() : $user;
        $this->surveySave($request, $user);

        $clients = $this->getClients($request);

        $canSave = true;
        if ( ! empty($request->text)) {
            $canSave *= $this->textValidate($request, $clients, $user);
        }

        if ( ! empty($request->email)) {
            $canSave *= $this->emailValidate($request);
        }

        if (empty($canSave)) {
            return 0;
        }

        $review = $user->reviews()->create([
            'survey_id' => $user->surveys()->first()->id,
        ]);

        $text = trim($user->surveys()->first()->text);

        foreach ($clients as $client) {
            $data = [
                'client_id' => $client['id'],
                'code' => $client['code'],
                'url' => $client['link'],
                'date' => $this->getDate($request->schedule, $request->time, $user),
                'type' => $this->getType($request->text, $request->email),
                'message' => ''
            ];

            $seance = $review->seances()->create($data);
            
            if ( ! empty($request->email)) {
                $this->sendEmail($client, $seance, $request->survey, $data['date']);
            }
        }

        if ( ! empty($request->text)) {
            $this->sendText($review, $clients, $text, $this->getDate($request->schedule, $request->time, $user));
        }

        return $this->message('Review was successfully saved', 'success');
    }

    private function textValidate($request, $clients, $user)
    {
        $date = '';
        if ( ! empty($request['schedule'])){
            $time = $request['time'];
            $date = Carbon::create($time['year'], $time['month'], $time['date'], $time['hours'], $time['minutes'], 0, config('app.timezone'));
        }

        if ( ! ApiValidate::companyExists($request->company, $user)) {
            return $this->message('This Company Name isn\'t verified');
        }

        if ( ! ApiValidate::companyVerified($request->company, $user)) {
            return $this->message('Company Name must be verified');
        }

        $text = trim($user->surveys()->first()->text);
        if ( ! ApiValidate::messageSymbols($text)) {
            return $this->message('SMS Text contains forbidden characters');
        }

        $length = true;
        $phones = true;
        $limit = true;
        foreach ($clients as $client) {
            $message = $text;

            if ( ! empty($client['link'])) {
                $message = str_replace('[$Link]', $client['link'], $message);
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

            if (ApiValidate::underLimit($client['phone'], $date)) {
                $limit = false;
            }
        }

        if (empty($length)) {
            return $this->message('SMS Text is too long. Text will not be send');
        }

        if (empty($phones)) {
            return $this->message('Some client\'s phone numbers have wrong format. Text will not be send');
        }

        if (empty($limit)) {
            $this->message('Some client\'s phone numbers already received texts during last 24h. Text will not be send');
        }

        if (ApiValidate::underBlocking($this->getDate($request->schedule, $request->time, $user, true))) {
            return $this->message('You can\'t send texts before 9 AM. You can try to use Schedule Send');
        }

        return true;
    }

    private function emailValidate($request)
    {
        return true;
    }

    private function getClients($request)
    {
        $clients = [];
        foreach ($request->clients as $client) {
            $client['code'] = $this->code($request->time);
            $client['link'] = $this->url($client['code']);

            $clients[] = $client;
        }

        return $clients;
    }

    private function sendClients($clients, $text)
    {
        $result = [];
        foreach ($clients as $client) {
            $row = [
                'phone' => $client['phone'],
            ];

            if (strpos($text, '[$Link]') !== false) {
                $row['link'] = $client['link'];
            }

            if (strpos($text, '[$FirstName]') !== false) {
                $row['firstname'] = $client['firstname'];
            }

            if (strpos($text, '[$LastName]') !== false) {
                $row['lastname'] = $client['lastname'];
            }

            $result[] = $row;
        }

        return $result;
    }

    public function sendText($review, $clients, $text, $date)
    {
        $delay = Carbon::now()->diffInSeconds($date);
        SendText::dispatch($review, $this->sendClients($clients, $text), $text, auth()->user()->company_name)->onQueue('texts')->delay($delay);
    }

    public function sendEmail($client, $seance, $survey, $date)
    {
        $delay = Carbon::now()->diffInSeconds($date);

        $job = (new SendEmail($client, $seance, $survey, auth()->user()->company_name))->delay($delay)->onQueue('emails');
        $this->dispatch($job);
    }

    private function surveySave($request, $user)
    {
        $data = [
            'text' => ! empty($request->survey['text']) ? $request->survey['text'] : '',
            'sender' => ! empty($request->survey['sender']) ? $request->survey['sender'] : '',
            'subject' => ! empty($request->survey['subject']) ? $request->survey['subject'] : '',
            'email' => ! empty($request->survey['email']) ? $request->survey['email'] : '',
        ];
        SurveysService::save($data, $user);
    }

    public function tap(Request $request, Seance $seance)
    {
        $seance->update([
            'url_id' => $request->url_id,
        ]);
    }

    public function getDate($schedule, $time, $user, $validate = false)
    {
        $date = Carbon::now()->subHours($user->offset);
        if ( ! empty($schedule)) {
            $date = Carbon::create($time['year'], $time['month'], $time['date'], $time['hours'], $time['minutes'], 0, config('app.timezone'));
        }

        if (empty($validate)) {
            $date->addHours($user->offset);
        }

        return $date;
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
        list($protocol, $url) = explode('://', ShortLink::bitly(config('app.url').'/seances/'.$code));
        //list($protocol, $url) = explode('://', Bitly::getUrl(config('app.url').'/seances/'.$code));
		return $url;
	}

    public function push(Request $request, Review $review)
    {
        $data = $request->json()->all();
        $clients = [];
        foreach ($data as $client) {
            $clients[$client['phone']] = $client;
        }

        SurveysService::seance($review, $clients);
    }
}
