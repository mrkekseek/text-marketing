<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Homeadvisor;
use App\User;
use App\Link;
use App\Client;
use App\Dialog;
use Bitly;
use App\Events\FirstLead;
use App\Jobs\SendHAEmail;
use App\Http\Requests\HACreateRequest;
use App\Jobs\SendLeadText;
use App\Libraries\Api;

class HomeadvisorController extends Controller
{
	public function info()
	{
		return auth()->user()->homeadvisors;
	}

	public function create(HACreateRequest $request)
	{
		$data = $request->only(['ha', 'user']);

		auth()->user()->update([
			'phone' => $data['user']['phone'],
		]);

		auth()->user()->homeadvisors()->create([
			'text' => $data['ha']['text'],
			'additional_phones' => $data['ha']['additional_phones'],
			'active' => $data['ha']['active'],
		]);

		return $this->message('Settings are successfully saved.', 'success');
	}

	public function update(HACreateRequest $request, Homeadvisor $homeadvisor)
	{
		$data = $request->only(['ha', 'user']);

		auth()->user()->update([
			'phone' => $data['user']['phone'],
		]);

		$homeadvisor->update([
			'text' => $data['ha']['text'],
			'additional_phones' => empty($data['ha']['additional_phones']) ? '' : $data['ha']['additional_phones'],
			'active' => $data['ha']['active'],
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

    	if ( ! empty($data)) {

    		$link = Link::where('code', $code)->first();
	    	if ( ! empty($link)) {
				$phone = $this->phone($data);

				$lead = [
					'firstname' => $this->firstName($data),
					'lastname' => $this->lastName($data),
					'phone' => $phone,
					'view_phone' => $phone,
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

    public function textToLead($user, $client, $ha)
    {
		$dialog = $user->dialogs()->create([
			'clients_id' => $client->id,
			'text' => $this->createText($user, $client, $ha),
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
        SendLeadText::dispatch($dialog, $phones, $dialog->text, $user)->onQueue('texts');
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
