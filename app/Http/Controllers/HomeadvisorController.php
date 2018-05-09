<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CsvReader;
use App\Homeadvisor;
use App\User;
use App\Team;
use App\Link;
use App\Client;
use App\Dialog;
use App\Alert;
use App\Picture;
use App\Lead;
use App\DefaultText;
use App\GeneralMessage;
use App\FreePlan;
use App\Mail\SendAlertClickEmail;
use DivArt\ShortLink\Facades\ShortLink;
use Propaganistas\LaravelPhone\PhoneNumber;
use Carbon\Carbon;
use App\Events\SaveLeadFromHomeadvisor;
use App\Http\Requests\HACreateRequest;
use App\Jobs\SendHAEmail;
use App\Jobs\SendLeadText;
use App\Jobs\SendAlertClick;
use App\Jobs\SendFollowUpText;
use App\Jobs\SendReferral;
use App\Jobs\SendGeneralText;
use App\Libraries\Api;
use App\Libraries\ApiValidate;
use App\Http\Services\UsersService;
use App\Http\Services\HomeAdvisorService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Google_Client;
use Google_Service_Calendar;
use Cartalyst\Stripe\Stripe;

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
		$info = auth()->user()->homeadvisors;
		$text = DefaultText::first();

		if ((empty($info->first_followup_delay) && empty($info->second_followup_delay)) || empty($info->text)) {
			$info->text = $text->instant;
			$info->first_followup_active = Homeadvisor::FIRST_FOLLOWUP_ACTIVE;
			$info->first_followup_delay = $text->first_followup_delay;
			$info->first_followup_text = $text->first_followup;
			$info->second_followup_active = Homeadvisor::SECOND_FOLLOWUP_ACTIVE;
			$info->second_followup_delay = $text->second_followup_delay;
			$info->second_followup_text = $text->second_followup;
			$info->save();
		}

		if (empty($info->click_alert_text)) {
			$info->click_alert_active = Homeadvisor::CLICK_ALERT_ACTIVE;
			$info->click_alert_text = $text->lead_clicks;
			$info->save();
		}

		$info->website = ! empty(auth()->user()->website) ? auth()->user()->website : '';
		$info->office_phone = ! empty(auth()->user()->office_phone) ? auth()->user()->office_phone : '';

		return $info;
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

		if ( ! empty($data['user']['website'])) {
			$url = str_replace(['http://', 'https://'], '', $data['user']['website']);
			$url_check = preg_match('|^(http(s)?://)?([a-z]+.)?[a-z0-9-]+\.([a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
			if (empty($url_check)) {
				return $this->message('Your Website url is not valid');
			}

			$website_shortlink = ShortLink::bitly('http://'.$url, false);
		}

		auth()->user()->update([
			'view_phone' => $data['user']['view_phone'],
			'phone' => $phone,
			'office_phone' => ! empty($data['user']['office_phone']) ? $data['user']['office_phone'] : '',
			'website' => ! empty($data['user']['website']) ? $data['user']['website'] : '',
			'website_shortlink' => ! empty($website_shortlink) ? $website_shortlink : '',
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
			'first_followup_active' => $data['ha']['first_followup_active'],
			'first_followup_text' => $data['ha']['first_followup_text'],
			'first_followup_delay' => $data['ha']['first_followup_delay'],
			'second_followup_active' => $data['ha']['second_followup_active'],
			'second_followup_text' => $data['ha']['second_followup_text'],
			'second_followup_delay' => $data['ha']['second_followup_delay'],
			'click_alert_active' => $data['ha']['click_alert_active'],
			'click_alert_text' => $data['ha']['click_alert_text'],
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

		$backup = Lead::create([
			'code' => $code,
			'data' => json_encode($data),
			'exists' => false,
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
				];

				$client = $link->user->teams->clients()->where('phone', $phone)->first();
				$lead_not_exists = true;
				if ( ! empty($client)) {
					$client->update($lead);
					$backup->update([
						'exists' => true,
					]);
					$lead_not_exists = false;
				} else {
					$client = $link->user->teams->clients()->create($lead);
				}

				$homeadvisor = $client->team->team_leader()->homeadvisors;

				if ( ! empty($link->user->phone)) {
					$phones[] = [
						'phone' => $link->user->phone,
						'firstname' => $client->firstname,
						'lastname' => $client->lastname,
						'website' => ! empty($link->user->website) ? $link->user->website : '',
						'office_phone' => ! empty($link->user->office_phone) ? $link->user->office_phone : '',
					];
					$temp[] = $link->user->phone;
				}

				if ( ! empty($homeadvisor->additional_phones)) {
					$numbers = explode(',', $homeadvisor->additional_phones);
					foreach ($numbers as $number) {
						$phone = $this->createPhone($number);
						if ( ! empty($phone)) {
							$phones[] = [
								'phone' => $phone,
								'firstname' => $client->firstname,
								'lastname' => $client->lastname,
								'website' => ! empty($link->user->website) ? $link->user->website : '',
								'office_phone' => ! empty($link->user->office_phone) ? $link->user->office_phone : '',
							];
							$temp[] = $phone;
						}
					}
				}

				if ($link->user->subscribed('Canceled')) {
					return $this->message('Your account is cenceled', 'error');
				}

				$exist_lead_delay = false;
				if ( ! $lead_not_exists && Carbon::now()->subHours(24) >= $client->created_at) {
					$exist_lead_delay = true;
				}

				if ($lead_not_exists || ( ! $lead_not_exists && $exist_lead_delay)) {
					event(new SaveLeadFromHomeadvisor($link->user, $client, $phones, $lead_not_exists));
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
			$client = $dialog->clients;
			$user = $client->team->team_leader();
			$parent_dialog = Dialog::where([['clients_id', '=', $dialog->clients_id], ['parent', '=', '1']])->first();

			if (( ! empty($user->phone) || ! empty($homeadvisor->additional_phones)) && empty($parent_dialog->clicked)) {
				$this->sendLeadClickText($user, $client);
			}

			$homeadvisor = $client->team->team_leader()->homeadvisors;

			$link = false;
			if ( ! empty($user->phone) || ! empty($homeadvisor->additional_phones) || ! empty($homeadvisor->emails)) {
				$link = $this->getMagicLink($user->id, $client->id, $dialog->id);
			}

			if (( ! empty($user->phone) || ! empty($homeadvisor->additional_phones)) && empty($dialog->clicked)) {
				$this->sendAlertClick($user, $homeadvisor, $client, $link, $dialog);
			}

			if ( ! empty($homeadvisor->emails)) {
				$this->sendAlertClickEmail($homeadvisor, $client, $link);
			}

			$parent_dialog->update(['clicked' => true]);
			$dialog->update(['clicked' => true]);
			$client->update(['clicked' => true]);
		}
		return redirect('http://bit.ly/'.$bitly);
	}

	private function getMagicLink($id, $client_id, $dialog_id)
	{
		return ShortLink::bitly(config('app.url').'/magic/inbox/'.$id.'/'.$client_id.'/'.$dialog_id, false);
	}

	private function sendLeadClickText($user, $client)
	{
		$free_plan_limit = FreePlan::checkLimit($user, $client);

		if (empty($free_plan_limit)) {
			return $this->message(__('You have reached your plan limit'), 'error');
		}

		$ha = $user->homeadvisors()->first();
		$default_text = DefaultText::first();
		$lead_text = empty($ha->click_alert_active) ? $default_text->lead_clicks : $ha->click_alert_text;
		$user_text = $default_text->lead_clicks_inbox;
		$client_data = [
			'users_id' => $user->id,
			'clients_id' => $client->id,
			'text' =>  $lead_text,
			'my' =>  true,
			'status' => 2,
		];

		$user_data = [
			'users_id' => $user->id,
			'clients_id' => $client->id,
			'text' =>  $user_text,
			'my' =>  true,
			'status' => 3,
		];

        $clients_phones[] = [
			'phone' => $client->phone,
			'firstname' => $client->firstname,
			'lastname' => $client->lastname,
			'website_shortlink' => ! empty($user->website_shortlink) ? $user->website_shortlink : '',
			'office_phone' => ! empty($user->office_phone) ? $user->office_phone : '',
		];

		$user_dialog = Dialog::create($user_data);
		$lead_dialog = Dialog::create($client_data);
		$delay_amount = Carbon::now()->addMinutes(15);
		$delay = Carbon::now()->diffInSeconds($delay_amount);

		SendLeadText::dispatch($lead_dialog, $clients_phones, $user)->delay($delay)->onQueue('texts');
	}

	public function sendAlertClick($user, $homeadvisor, $client, $link, $dialog)
	{
		$phones = [];
		$temp = [];
		$default_text = DefaultText::first();
		$text = $default_text->lead_clicks_alert;

		if ( ! empty($user->phone)) {
			$phones[] = [
				'phone' => $user->phone,
				'firstname' => $client->firstname,
				'lastname' => $client->lastname,
				'link' => $link,
			];
			$temp[] = $user->phone;
		}

		if ( ! empty($homeadvisor->additional_phones)) {
			$numbers = explode(',', $homeadvisor->additional_phones);
			foreach ($numbers as $number) {
				$phone = $this->createPhone($number);
				if ( ! empty($phone)) {
					$phones[] = [
						'phone' => $phone,
						'firstname' => $client->firstname,
						'lastname' => $client->lastname,
						'link' => $link,
					];
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
			SendAlertClick::dispatch($alert, $phones, $text, $user, $dialog)->onQueue('texts');
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

	public function sendReferral(Request $request)
	{
		$data = $request->only(['name', 'contacts']);
		$owner = User::where('owner', 1)->first();

		SendReferral::dispatch(auth()->user(), $owner, $data)->onQueue('emails');

		return $this->message('Information about referral was sent', 'success');
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

	public function getGeneralMessages()
	{
		return array_values(GeneralMessage::orderBy('created_at', 'desc')->get()->each(function($item, $key) {
            Carbon::setToStringFormat('F dS g:i A');
			$item->created_at_string = $item->created_at->__toString();
            Carbon::resetToStringFormat();
			return $item;
		})->unique('phone')->toArray());
	}

	public function getGeneralMessageWithUser($phone)
	{
		return GeneralMessage::where('phone', $phone)->get()->each(function($item, $key) {
			$user = User::where('phone', $item->phone)->first();
			$item->user_firstname = ! empty($user->firstname) ? $user->firstname : '';
			$item->user_lastname = ! empty($user->lastname) ? $user->lastname : '';
            Carbon::setToStringFormat('F dS g:i A');
			$item->created_at_string = $item->created_at->__toString();
            Carbon::resetToStringFormat();
			return $item;
        });
	}

	public function inbox(Request $request, GeneralMessage $message)
    {
        $data = $request->only(['CONTENTS']);
        $message = $message->replicate();
        $message->text = $data['CONTENTS'];
        $message->new = true;
        $message->status = 1;
        $message->my = false;
        $message->save();
	}

	public function push(Request $request, GeneralMessage $message)
    {
		$data = $request->json()->all();
        $status = 2;
        foreach ($data as $client) {
            if ( ! empty($client['finish'])) {
                $status = 0;
                if ( ! empty($client['success'])) {
                    $status = 1;
                }
            }
        }

        $message->update([
            'status' => $status
        ]);
    }

	public function sendGeneralMessage(Request $request, GeneralMessage $message)
    {
		$data = $request->only(['text', 'time']);

		if ($this->textValidate($data, $message, false)) {
			$general_message = new GeneralMessage();
			$general_message->type = $message->type;
			$general_message->phone = $message->phone;
			$general_message->text = $data['text'];
			$general_message->my = true;
			$general_message->status = 2;
			$general_message->save();

            $phones = [];
			$phones[] = ['phone' => $message->phone];
			$offset = 0;

            SendGeneralText::dispatch($general_message, $phones, $data['text'], 'ContractorTexter', $offset)->onQueue('texts');

			$this->message(__('Message was send'), 'success');
			return $general_message;
		}
	}

	public function textValidate($data, $message, $block = true)
    {
        $text = trim($data['text']);
        if ( ! ApiValidate::messageSymbols($text)) {
            return $this->message('SMS Text contains forbidden characters');
        }

        if ( ! empty($message)) {
            $length = true;
            $phones = true;
            $limit = true;

            if ( ! ApiValidate::messageLength($text, auth()->user()->company_name)) {
                $length = false;
            }

            if ( ! ApiValidate::phoneFormat($message->phone)) {
                $phones = false;
            }

            if (empty($length)) {
                return $this->message('SMS Text is too long. Text will not be send');
            }

            if (empty($phones)) {
                return $this->message('Some client\'s phone numbers have wrong format. Text will not be send');
            }
        }

        $date = (object)$data['time'];
        if (ApiValidate::underBlocking($date, $block)) {
            return $this->message('You can\'t send texts before 9 AM. You can try to use Schedule Send');
        }

        return 1;
	}

	/* public function nexmo()
    {
		$base_url = 'https://api.nexmo.com' ;

		$jwt = $this->generateJwt(file_get_contents('../private.key'), env('NEXMO_APPLICATION_ID'));

		$client = new Guzzle(['base_uri' => $base_url]);
        $response = $client->request('GET', '/v1/calls', [
            'headers' => [
				'Authorization' => 'Bearer ' . $jwt,
				'Content-Type' => 'application/json'
			],
			'http_errors' => false,
		]);

		dd(json_decode($response->getBody(), true));
	}

	public function createNexmoVoiceApp()
    {
		$base_url = 'https://api.nexmo.com' ;
		$version = '/v1';
		$action = '/applications/?';

		$url = $base_url . $version . $action . http_build_query([
			'api_key' =>  env('NEXMO_KEY'),
			'api_secret' => env('NEXMO_SECRET'),
			'name' => 'Receive Calls Application',
			'type' => 'voice',
			'answer_url' => 'https://splendid-panda-96.localtunnel.me/homeadvisor/answer',
			'event_url' => 'https://splendid-panda-96.localtunnel.me/homeadvisor/event'
		]);

		$client = new Guzzle(['base_uri' => $url]);
		$response = $client->request('POST');

		$data = json_decode($response->getBody(), true);

		$application_id = $data['id']; //можна записать в базу айдішкі аплікейшенів 480bae0c-9970-49d8-86e7-ac92cd77ce5f або глянуть на сайті в дашборді і записать в .env
		file_put_contents('../private.key', $data['keys']['private_key']);
	}

	function generateJwt($key, $application_id)
	{
		$jwt = false;
		date_default_timezone_set('UTC');    //Set the time for UTC + 0
		$signer = new Sha256();
		$privateKey = new Key($key);

		$jwt = (new Builder())->setIssuedAt(time() - date('Z')) // Time token was generated in UTC+0
			->set('application_id', $application_id) // ID for the application you are working with
			->setId( base64_encode( mt_rand (  )), true)
			->sign($signer,  $privateKey) // Create a signature using your private key
			->getToken(); // Retrieves the JWT

		return $jwt;
	} */

	function nexmoCall()
	{
		$client = app('Nexmo\Client');

		$response = $client->insights()->advancedCnam('9192598619');
		dd($response);
		/* $response = $client->insights()->advancedCnam('19413505601'); */

		/* $response['current_carrier']['network_type'];
		$response['first_name'];
		$response['last_name']; */

		/* $request = $client->calls()->create([
			'to' => [[
				'type' => 'phone',
				'number' => '380958067064'
			]],
			'from' => [
				'type' => 'phone',
				'number' => '12017309896'
			],
			'answer_url' => ['http://34.218.79.76/api/v1/homeadvisor/answer'],
			'event_url' => ['http://34.218.79.76/api/v1/homeadvisor/event'],
		]); */
	}

	public function event(Request $request)
    {

	}

	public function answer(Request $request)
    {
		$client = app('Nexmo\Client');

		$caller_phone = $request->from;
		$caller = $client->insights()->basic($caller_phone);
		$phone = $caller['national_format_number'];
		$exists = Client::where('phone', $phone)->exists();

		if (! empty($phone) && ! $exists)
		{
			$backup = Lead::create([
				'code' => $request->uuid,
				'data' => json_encode($caller),
				'exists' => false,
			]);

			$lead = new Client();
			$lead->firstname = ! empty($caller['first_name']) ? $caller['first_name'] : '';
			$lead->lastname = ! empty($caller['last_name']) ? $caller['last_name'] : '';
			$lead->phone = $phone;
			$lead->view_phone = $phone;
			$lead->source = 'Vonage';
			$lead->save();
		}
    }

	/* public function event(Request $request)
    {
		$method = $_SERVER['REQUEST_METHOD'];
		$request = array_merge($_GET, $_POST);

		switch ($method) {
			case 'POST':
				//Retrieve your dynamically generated NCCO.
				$ncco = $this->handle_call_status();
				header("HTTP/1.1 200 OK");
				break;
			default:
				//Handle your errors
				$this->handle_error($request);
				break;
		}

		$nexmo = new NexmoCall();
		$nexmo->uuid = ! empty($request->from) ? 'event '.$request->from : 'event';
		$nexmo->conversation_uuid = ! empty($request->to) ? 'event '.$request->to : 'event';
		$nexmo->save();

		file_put_contents('my_log.txt', 'event_webhook: '.print_r($request), FILE_APPEND | LOCK_EX);
	}

	public function handle_call_status()
	{
		$decoded_request = json_decode(file_get_contents('php://input'), true);
		$nexmo = new NexmoCall();
		$nexmo->uuid = 'handle_call_status';
		$nexmo->conversation_uuid = 'handle_call_status';
		$nexmo->save();
		// Work with the call status
		if (isset($decoded_request['status'])) {
			switch ($decoded_request['status']) {
			case 'ringing':
				echo("Handle conversation_uuid, this return parameter identifies the Conversation");
				break;
			case 'answered':
				echo("You use the uuid returned here for all API requests on individual calls");
				break;
			case 'complete':
				//if you set eventUrl in your NCCO. The recording download URL
				//is returned in recording_url. It has the following format
				//https://api.nexmo.com/media/download?id=52343cf0-342c-45b3-a23b-ca6ccfe234b0
				//Make a GET request to this URL using a JWT as authentication to download
				//the Recording. For more information, see Recordings.
				break;
			default:
				break;
		}
			return;
		}
	}

	public function handle_error($request)
	{
		$nexmo = new NexmoCall();
		$nexmo->uuid = 'handle_error';
		$nexmo->conversation_uuid = 'handle_error';
		$nexmo->save();
		dd($request);
	} */

	public function googleCalendar()
	{
		dd(ShortLink::expand('bit.ly/2J2mcEy'));
		$client = new Google_Client();
		$client->setAuthConfig('../client_secret.json');
		$client->addScope(Google_Service_Calendar::CALENDAR);
		$redirect_uri = 'https://new-dodo-66.localtunnel.me/api/v1/homeadvisor/token';
		$client->setRedirectUri($redirect_uri);
		$auth_url = $client->createAuthUrl();
		dd($auth_url);
		return redirect($auth_url);

		/* $service = new Google_Service_Calendar($client);

		$calendarId = 'div-art.com_d14idkjg9ik72p7pebgi3do25c@group.calendar.google.com';

		$results = $service->events->listEvents($calendarId);
		dd($events); */
	}

	public function getCalendarToken()
	{
		if (isset($_GET['code'])) {
			$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
		}
	}
}
