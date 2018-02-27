<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Homeadvisor;
use App\User;
use App\Team;
use App\Link;
use App\Client;
use App\Dialog;
use App\Alert;
use App\Picture;
use App\Lead;
use App\Mail\SendAlertClickEmail;
use DivArt\ShortLink\Facades\ShortLink;
use Carbon\Carbon;
use App\Events\FirstLead;
use App\Jobs\SendHAEmail;
use App\Http\Requests\HACreateRequest;
use App\Jobs\SendLeadText;
use App\Jobs\SendAlertClick;
use App\Jobs\SendFollowUpText;
use App\Libraries\Api;
use App\Libraries\ApiValidate;
use App\Http\Services\UsersService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class HomeadvisorController extends Controller
{
	public function convert(Request $request)
	{
		$leads = $request->json();
		foreach ($leads as $lead) {
			if ( ! Client::where('phone', $lead['phone'])->count()) {
				$newLead = [
					'firstname' => $lead['firstname'],
					'lastname' => $lead['lastname'],
					'phone' => $lead['phone'],
					'view_phone' => $lead['view_phone'],
					'email' => $lead['emails'],
					'source' => 'HomeAdvisor',
					'hapage' => 0,
					'created_at' => Carbon::parse($lead['created_at']),
				];

				$team = Team::find($lead['team_id']);
				$client = $team->clients()->create($newLead);
			}
		}
	}

	public function info()
	{
		return auth()->user()->homeadvisors;
	}

	public function pictures()
	{
		return auth()->user()->pictures;
	}

	public function picturesRemove(Request $request)
	{
		$file = false;
		if (empty($request->picture['id'])) {
			$temp = explode('/temp/', $request->picture['url']);
			$file = 'temp/'.$temp[1];
		} else {
			$temp = explode('/pictures/', $request->picture['url']);
			$file = 'pictures/'.$temp[1];

			Picture::destroy($request->picture['id']);
		}
		Storage::disk('s3')->delete($file);
		return 'ok';
	}

	public function page(User $user, Client $client = null)
	{
		if ( ! empty($client)) {
			$client->update([
				'hapage' => true,
			]);
		}

		if ($user->plans_id != 'home-advisor-'.strtolower(config('app.name')) && $user->plans_id != 'text-'.strtolower(config('app.name'))) {
			return view('ha.forbidden');
		} else {
			$pictures = $user->pictures;
			$ha = $user->homeadvisors;
			$link = $this->getBitlyLink($ha->text);

			return view('ha.page')->with(compact('user', 'ha', 'pictures', 'link'));
		}
	}

	public function getBitlyLink($text)
	{
		$link = '';
		$linkPos = strpos($text, 'bit.ly/');
    	if ($linkPos !== false) {
    		$link = substr($text, $linkPos, 14);
		}
		return $link;
	}

	public function create(HACreateRequest $request)
	{
		$data = $request->only(['ha', 'user']);
		$file = '';

		$phone = UsersService::phoneToNumber($data['user']);

		ApiValidate::phoneFormat($phone);
		
		auth()->user()->update([
			'view_phone' => $data['user']['view_phone'],
			'phone' => $phone,
		]);

		$data['ha']['text'] = str_replace("\n", "", $data['ha']['text']);
		//$data['ha']['text'] = str_replace("'", "‘", $data['ha']['text']);

		if ( ! ApiValidate::messageSymbols($data['ha']['text'])) {
			return $this->message('Text containes forbidden characters');
		}

		if ( ! empty($data['ha']['file'])) {
			$temp = explode('.', $data['ha']['file']);
			$name = auth()->user()->id.'.'.$temp[1];
			Storage::move(str_replace('storage', 'public', $data['ha']['file']), 'public/upload/homeadvisor/'.auth()->user()->id.'/'.$name);
			$file = 'storage/upload/homeadvisor/'.auth()->user()->id.'/'.$name;
		}

		auth()->user()->homeadvisors()->create([
			'text' => $data['ha']['text'],
			'additional_phones' => $data['ha']['additional_phones'],
			'emails' => $data['ha']['emails'],
			'active' => $data['ha']['active'],
			'file' => $file,
		]);

		return $this->message('Settings are successfully saved.', 'success');
	}

	public function update(HACreateRequest $request, Homeadvisor $homeadvisor)
	{
		$data = $request->only(['ha', 'user', 'pictures']);
		$file = '';

		foreach ($data['pictures'] as $pos => $picture) {
			if (empty($picture['id'])) {
				$temp = explode('/temp/', $picture['url']);
				Storage::disk('s3')->move('temp/'.$temp[1], 'pictures/'.$temp[1]);
				auth()->user()->pictures()->create([
					'url' => Storage::disk('s3')->url('pictures/'.$temp[1]),
					'pos' => $pos,
				]);
			}
		}
		
		$phone = UsersService::phoneToNumber($data['user']);

		ApiValidate::phoneFormat($phone);
		
		auth()->user()->update([
			'view_phone' => $data['user']['view_phone'],
			'phone' => $phone,
		]);

		$data['ha']['text'] = str_replace("\n", "", $data['ha']['text']);
		//$data['ha']['text'] = str_replace("'", "‘", $data['ha']['text']);

		if ( ! ApiValidate::messageSymbols($data['ha']['text'])) {
			return $this->message('Text containes forbidden characters');
		}

		if ( ! empty($data['ha']['file'])) {
			$temp = explode('.', $data['ha']['file']);
			$name = auth()->user()->id.'.'.$temp[1];
			if (strpos($data['ha']['file'], 'temp') !== false) {
				Storage::deleteDirectory('public/upload/homeadvisor/'.auth()->user()->id);
				Storage::copy(str_replace('storage', 'public', $data['ha']['file']), 'public/upload/homeadvisor/'.auth()->user()->id.'/'.$name);
			}
			$file = 'storage/upload/homeadvisor/'.auth()->user()->id.'/'.$name;
		} else {
			Storage::deleteDirectory('public/upload/homeadvisor/'.auth()->user()->id);
		}

		$homeadvisor->update([
			'text' => $data['ha']['text'],
			'additional_phones' => empty($data['ha']['additional_phones']) ? '' : $data['ha']['additional_phones'],
			'emails' => empty($data['ha']['emails']) ? '' : $data['ha']['emails'],
			'active' => $data['ha']['active'],
			'file' => $file,
		]);

		return $this->message('Settings are successfully saved.', 'success');
	}

    public function activate()
    {
		auth()->user()->homeadvisors()->create([
			'send_request' => true,
			'text' => '',
			'emails' => '',
		]);

    	$this->sendActivateEmail();

    	return $this->message('Your request successfully sent.', 'success');
	}

	public function activateUpdate(Homeadvisor $homeadvisor)
	{
		$homeadvisor->update([
			'send_request' => true,
		]);

		$this->sendActivateEmail();
    	return $this->message('Your request successfully sent.', 'success');
	}
	
	public function enable(Homeadvisor $homeadvisor)
    {
		auth()->user()->homeadvisors()->update([
			'active' => ! auth()->user()->homeadvisors->active,
		]);

    	return $this->message('HomeAdvisor settings were saved', 'success');
    }

    public function sendActivateEmail()
    {
    	$owner = User::where('owner', true)->first();
		$user = auth()->user();
		$ha = auth()->user()->homeadvisors;
		$link = auth()->user()->links;
		
		SendHAEmail::dispatch($user, $owner, $ha, $link)->onQueue('emails');
    }

    public function lead(Request $request, $code)
    {
		$data = $request->json()->all();

		if (empty($data)) {
			$data = $request->all();
		}

		$this->saveLog($code, 'HomeAdvisor CODE');
		$this->saveLog($data, 'HomeAdvisor data');

		$backup = Lead::create([
			'code' => $code,
			'data' => json_encode($data),
		]);

    	if ( ! empty($data)) {
			$link = Link::where('code', $code)->first();
			$backup->update([
				'user_id' => $link->user->id,
				'team_id' => $link->user->teams->id,
			]);

	    	if ( ! empty($link)) {
				$view_phone = $this->phone($data);
				$phone = str_replace(['-', '(', ')', ' ', '.', '_'], '', $view_phone);

				$lead = [
					'firstname' => $this->firstName($data),
					'lastname' => $this->lastName($data),
					'phone' => $phone,
					'view_phone' => $view_phone,
					'email' => $this->email($data),
					'source' => 'HomeAdvisor',
					'hapage' => 0,
				];

				$client = $link->user->teams->clients()->where('phone', $phone)->first();
				if ( ! empty($client)) {
					$client->update($lead);
					$backup->update([
						'exists' => true,
					]);
				} else {
					$client = $link->user->teams->clients()->create($lead);
				}

				if ($link->user->teams->clients()->where('source', 'HomeAdvisor')->count() == 1) {
					$owner = User::where('owner', true)->first();
					event(new FirstLead($link->user, $owner, $link->user->homeadvisors));
				}

				$ha = $link->user->homeadvisors;
				if ( ! empty($ha->active) && ! empty($ha->text) && ! empty($phone)) {
					$this->textToLead($link->user, $client, $ha);
				}

				$backup->update([
					'saved' => true,
				]);

				http_response_code(200);
				echo '<success>User '.$code.'</success>';
				return;
			}
			
			http_response_code(500);
			echo '<error>Bad request</error>';
			return;
    	}

    	http_response_code(500);
		echo '<error>Data required</error>';
		return;
    }

    public function saveLog($data, $source)
    {
    	if ( ! file_exists('logs')) {
			mkdir('logs', 0777);
		}
		file_put_contents('logs/logger.txt', date('[Y-m-d H:i:s] ').$source.': '.print_r($data, true).PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public function textToLead($user, $client, $ha)
    {
		$dialog = $user->dialogs()->create([
			'clients_id' => $client->id,
			'text' => '',
			'file' => ! empty($ha->file) ? $ha->file : '',
			'my' => true,
			'status' => 2,
		]);

		$text = $this->createText($user, $client, $ha, $dialog);
		$dialog->update(['text' => $text]);

		$row = [
            'phone' => $client->phone,
        ];

        if (strpos($dialog->text, '[$FirstName]') !== false) {
            $row['firstname'] = $client->firstname;
        }

        if (strpos($dialog->text, '[$LastName]') !== false) {
            $row['lastname'] = $client->lastname;
        }

		$phones[] = $row;
		
		$date = Carbon::now()->addHour();
		$from = Carbon::now()->addHour()->subHour($user->offset);
		$to = Carbon::now()->addHour(7)->subHour($user->offset);

		if ($date->hour >= $from->hour && $date->hour < $to->hour) {
			$date = $to;
			$date->minute = 1;
		}
		$delay = Carbon::now()->diffInSeconds($date);

		SendLeadText::dispatch($dialog, $phones, $user)->onQueue('texts');
		SendFollowUpText::dispatch($dialog, $phones, $user)->delay($delay)->onQueue('texts');
    }

    public function createText($user, $client, $ha, $dialog)
    {
    	$text = $ha->text;
    	$linkPos = strpos($text, 'bit.ly/');
    	if ($linkPos !== false) {
    		$originLink = substr($text, $linkPos, 14);
    		$fakeLink = ShortLink::bitly(config('app.url').'/magic/'.$dialog->id.'/'.$originLink, false);
    		$text = str_replace($originLink, $fakeLink, $text);
		}
		
		if (strpos($text, '[$JobPics]') !== false) {
			$hapage = ShortLink::bitly(config('app.url').'/ha-job/'.$user->id.'/'.$client->id, false);
    		$text = str_replace('[$JobPics]', $hapage, $text);
		}

    	return $text;
	}

	public function sendFake(Request $request)
	{
		$data = $request->only(['firstname', 'lastname', 'phone', 'code']);
		$data['lastname'] = ! empty($data['lastname']) ? $data['lastname'] : '';
		$post = '{"firstName":"'.$data['firstname'].'","lastName":"'.$data['lastname'].'","address1":"3103 avenue i","city":"Brooklyn","state":"NY","postalCode":"11210","phonePrimary":"'.$data['phone'].'","phoneSecondary":"6465300170","email":"rabbinturk@gmail.com","srOid":94357914,"leadOid":249583859,"taskOid":2102382,"fee":70.49,"taskName":"Closet - Build","comments":"Customer did not provide additional comments.  Please contact the customer to discuss the details of this project.","interview":"Location of Closet:Master Bedroom; Closet Features:Built-in shelves; Part of a larger home remodel?:No; Request Stage:Ready to Hire; Desired Completion Date:Timing is flexible; What kind of location is this?:Home/Residence; Historical Work:No; Property Owner:Yes;","matchType":"exact","leadDescription":"Exact Match","spEntityId":65021820,"spCompanyName":"TB Carpentry","subject":"Closet - Build","zip":"11210","name":"tester tester","primary_phone":"508617135","lead_id":249583859}';
		$post = json_decode($post, true);
		$uri = '/home-advisor/'.$data['code'];
		$response = Api::sendFake($uri, $post);
		if ($response['code'] == 200) {
			return $this->message('HomeAdvisor Lead was created', 'success');
		}
	}

	public function magic(Dialog $dialog, $bitly)
	{
		$this->saveLog($_SERVER, 'MAGIC CLICK');
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'bitlybot') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'TweetmemeBot') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'HttpClient') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'UNAVAILABLE') === false) {
			$dialog->update(['clicked' => true]);
			$client = $dialog->clients;
			$client->update(['clicked' => true]);
			$user = $client->team->team_leader();
			$homeadvisor = $client->team->team_leader()->homeadvisors;
			
			$link = false;
			if ( ! empty($user->phone) || ! empty($homeadvisor->additional_phones) || ! empty($homeadvisor->emails)) {
				$link = $this->getMagicLink($user->id, $client->id);
			}

			if (( ! empty($user->phone) || ! empty($homeadvisor->additional_phones)) && empty($dialog->clicked)) {
				$this->sendAlertClick($user, $homeadvisor, $client, $link);
			}

			if ( ! empty($homeadvisor->emails)) {
				$this->sendAlertClickEmail($homeadvisor, $client, $link);
			}
		}
		return redirect('http://bit.ly/'.$bitly);
	}

	private function getMagicLink($id, $client_id)
	{
		return ShortLink::bitly(config('app.url').'/magic/inbox/'.$id.'/'.$client_id, false);
	}

	public function sendAlertClick($user, $homeadvisor, $client, $link)
	{
		$phones = [];
		$temp = [];
		
		$text = 'Hi, Lead '.$client->firstname.' just clicked on the link in your text and is a very hot lead. Try to reach them ASAP - '.$link.'!';
		
		if ( ! empty($user->phone)) {
			$phones[]['phone'] = $user->phone;
			$temp[] = $user->phone;
		}

		if ( ! empty($homeadvisor->additional_phones)) {
			$numbers = explode(',', $homeadvisor->additional_phones);
			foreach ($numbers as $number) {
				$phone = $this->createPhone($number);
				if ( ! empty($phone)) {
					$phones[]['phone'] = $phone;
					$temp[] = $phone;
				}
			}
		}
		if ( ! empty($phones)) {
			$data = [
				'user_id' => $user->id,
				'phone' => implode(',', $temp),
				'text' => $text,
			];
			$alert = Alert::create($data);
			SendAlertClick::dispatch($alert, $phones, $text, $user)->onQueue('texts');
		}
	}

	public function sendAlertClickEmail($homeadvisor, $client, $link)
	{
		$temp = [];
		if ( ! empty($homeadvisor->emails)) {
			$emails = explode(',', $homeadvisor->emails);
			foreach ($emails as $email) {
				$temp[] = $email;
			}
		}

		if ( ! empty($temp)) {
            $message = (new SendAlertClickEmail($client->firstname, 'https://'.$link))
                ->onQueue('emails');
            Mail::to($temp)->queue($message);
        }
	}

	public function createPhone($number)
	{
		$phone = str_replace(['-', '(', ')', ' ', '.'], '', $number);
		if (ApiValidate::phoneFormat($phone)) {
			return $phone;
		}
		return false;
	}
	
	public function firstName($data)
	{
		return ! empty($data['firstName']) ? $data['firstName'] : (! empty($data['first_name']) ? $data['first_name'] : '');
	}

	public function lastName($data)
	{
		return ! empty($data['lastName']) ? $data['lastName'] : ( ! empty($data['last_name']) ? $data['last_name'] : '');
	}

    public function phone($data)
    {
    	return ! empty($data['phonePrimary']) ? $data['phonePrimary'] : ( ! empty($data['primaryPhone']) ? $data['primaryPhone'] : ( ! empty($data['phone_primary']) ? $data['phone_primary'] : false));
	}
	
	public function email($data)
	{
		return ! empty($data['email']) ? $data['email'] : '';
	}
}
