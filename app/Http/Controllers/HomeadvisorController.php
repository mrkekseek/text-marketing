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
		/* $info['website'] = ! empty(auth()->user()->website) ? auth()->user()->website : '';
		$info['office_phone'] = ! empty(auth()->user()->office_phone) ? auth()->user()->office_phone : ''; */
		$text = DefaultText::first();

		if (empty($info->first_followup_delay) && empty($info->second_followup_delay)) {
			$info->first_followup_active = Homeadvisor::FIRST_FOLLOWUP_ACTIVE;
			$info->first_followup_delay = $text->first_followup_delay;
			$info->first_followup_text = $text->first_followup;
			$info->second_followup_active = Homeadvisor::SECOND_FOLLOWUP_ACTIVE;
			$info->second_followup_delay = $text->second_followup_delay;
			$info->second_followup_text = $text->second_followup;
			$info->save();
		}

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
				$lead_exists = true;
				if ( ! empty($client)) {
					$client->update($lead);
					$backup->update([
						'exists' => true,
					]);
					$lead_exists = false;
				} else {
					$client = $link->user->teams->clients()->create($lead);
				}

				$homeadvisor = $client->team->team_leader()->homeadvisors;

				if ( ! empty($link->user->phone)) {
					$phones[] = [
						'phone' => $link->user->phone,
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
							];
							$temp[] = $phone;
						}
					}
				}
				
				event(new SaveLeadFromHomeadvisor($link->user, $client, $phones, $lead_exists));

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
			
			if (( ! empty($user->phone) || ! empty($homeadvisor->additional_phones)) && empty($dialog->clicked)) {
				$this->sendLeadClickText($user, $client);
			}
			
			$dialog->update(['clicked' => true]);
			$client->update(['clicked' => true]);
			
			$homeadvisor = $client->team->team_leader()->homeadvisors;
			
			$link = false;
			if ( ! empty($user->phone) || ! empty($homeadvisor->additional_phones) || ! empty($homeadvisor->emails)) {
				$link = $this->getMagicLink($user->id, $client->id);
			}

			if (( ! empty($user->phone) || ! empty($homeadvisor->additional_phones)) && ! empty($dialog->clicked)) {
				$this->sendAlertClick($user, $homeadvisor, $client, $link, $dialog);
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
	
	private function sendLeadClickText($user, $client)
	{
		$default_text = DefaultText::first();
		$lead_text = $default_text->lead_clicks;
		$client_data = [
			'users_id' => $user->id,
			'clients_id' => $client->id,
			'text' =>  $lead_text,
			'my' =>  true,
			'status' => 2,
		];

        $clients_phones[] = [
			'phone' => $client->phone,
			'firstname' => $client->firstname,
		];
		
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

	public function lookup(Request $request)
	{
		$data = $request->only('url');
		$url = $data['url'][0];
		$numbers = file($url);
		$result = [];
		
		foreach ($numbers as $item) {
			$number = trim($item);
			try {
				$number_type = PhoneNumber::make($number, 'US')->getType();
				$number_formated = PhoneNumber::make($number, 'US')->formatE164();
				if ($number_type == 'mobile' || $number_type == 'fixed_line_or_mobile') {
					$result[] = str_replace('+1', '', $number_formated);
				}
			}
			catch(\libphonenumber\NumberParseException $e) {
				//print_r($e);
			}
		}

		$default_text = DefaultText::first();
		$text = $default_text->new_user;
		
		if ( ! empty($result)) {
			foreach ($result as $phone) {
				if ( ! GeneralMessage::where('phone', $phone)->exists()) {
					$global_dialog = new GeneralMessage();
					$global_dialog->type = 'twilio';
					$global_dialog->phone = $phone;
					$global_dialog->text = $text;
					$global_dialog->my = 1;
					$global_dialog->status = 0;
					$global_dialog->save();

					$phones = [];
					$phones[] = ['phone' => $phone];
					$offset = 0;

					SendGeneralText::dispatch($global_dialog, $phones, $text, 'ContractorTexter', $offset)->onQueue('texts');
				}
			}
		}
	}

	public function getGeneralMessages()
	{
		return array_values(GeneralMessage::all()->each(function($item, $key) {
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
			$item->user_firstname = $user->firstname;
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
}
