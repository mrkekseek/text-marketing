<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Homeadvisor;
use App\User;
use App\Link;
use App\Client;
use App\Dialog;
use App\Alert;
use Bitly;
use App\Events\FirstLead;
use App\Jobs\SendHAEmail;
use App\Http\Requests\HACreateRequest;
use App\Jobs\SendLeadText;
use App\Jobs\SendAlertClick;
use App\Libraries\Api;
use App\Libraries\ApiValidate;
use Illuminate\Support\Facades\Storage;

class HomeadvisorController extends Controller
{
	public function info()
	{
		return auth()->user()->homeadvisors;
	}

	public function create(HACreateRequest $request)
	{
		$data = $request->only(['ha', 'user']);
		$file = '';

		auth()->user()->update([
			'phone' => $data['user']['phone'],
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
			'active' => $data['ha']['active'],
			'file' => $file,
		]);

		return $this->message('Settings are successfully saved.', 'success');
	}

	public function update(HACreateRequest $request, Homeadvisor $homeadvisor)
	{
		$data = $request->only(['ha', 'user']);
		$file = '';
		auth()->user()->update([
			'phone' => $data['user']['phone'],
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
    	$this->saveLog($_POST, 'HomeAdvisor POST');
		$this->saveLog($_GET, 'HomeAdvisor GET');
		$this->saveLog($request->all(), 'HomeAdvisor request->all()');
		$this->saveLog($request->json()->all(), 'HomeAdvisor request->json()->all()');
		$input = file_get_contents("php://input");
		$this->saveLog($input, 'HomeAdvisor input');

		$data = $request->json()->all();

		if (empty($data)) {
			$data = $request->all();
		}

		$this->saveLog($data, 'HomeAdvisor data');

    	if ( ! empty($data)) {

    		$link = Link::where('code', $code)->first();
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
				];
				
				$client = $link->user->teams->clients()->where('phone', $phone)->first();
				if ( ! empty($client)) {
					$client->update($lead);
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


				http_response_code(200);
				echo '<success>User '.$code.'</success>';
				exit;
			}
			
			http_response_code(500);
			echo '<error>Bad request</error>';
			exit;
    	}

    	http_response_code(500);
		echo '<error>Data required</error>';
		exit;
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
			'text' => $this->createText($user, $client, $ha),
			'file' => ! empty($ha->file) ? $ha->file : '',
			'my' => true,
			'status' => 2,
		]);

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

        SendLeadText::dispatch($dialog, $phones, $user)->onQueue('texts');
    }

    public function createText($user, $client, $ha)
    {
    	$text = $ha->text;
    	$linkPos = strpos($text, 'bit.ly/');
    	if ($linkPos !== false) {
    		$originLink = substr($text, $linkPos, 14);
    		$fakeLink = Bitly::getUrl(config('app.url').'/magic/'.$client->id.'/'.$originLink);
    		$fakeLink = str_replace('http://', '', $fakeLink);
    		$text = str_replace($originLink, $fakeLink, $ha->text);
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

	public function magic(Client $client, $bitly)
	{
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'bitlybot') === false) {
			$client->update(['clicked' => true]);
			$user = $client->team->team_leader();
			$homeadvisor = $client->team->team_leader()->homeadvisors;
			
			if ( ! empty($user->phone) || ! empty($homeadvisor->additional_phones)) {
				$this->sendAlertClick($user, $homeadvisor, $client);
			}
		}
		return redirect('http://bit.ly/'.$bitly);
	}

	public function sendAlertClick($user, $homeadvisor, $client)
	{
		$phones = [];
		$temp = [];
		$link = Bitly::getUrl(config('app.url').'/ha/user/');
		$link = str_replace('http://', '', $link);
		$text = 'Hi, Lead '.$client->firstname.' just clicked on the link in your text and is a very hot lead. Try to reach them ASAP - '.$link.'!';
		$test = 'Hi '.$user->firstname .', a lead just texted you a reply. Please click '.$link.' to see it and reply if you like - thanks!';
		
		if ( ! empty($user->phone)) {
			$phones[]['phone'] = $user->phone;
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
