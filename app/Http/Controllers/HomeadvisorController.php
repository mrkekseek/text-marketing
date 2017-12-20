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
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivateHa;

class HomeadvisorController extends Controller
{
	public function info($id = false, $post = [])
	{
		return Homeadvisor::where('users_id', auth()->user()->id)->first();
	}

	public function save($id = false, $post = [])
	{
		$homeadvisor = Homeadvisor::find($id);
		$homeadvisor->users_id = auth()->user()->id;
		$homeadvisor->text = ! empty($post['text']) ? $post['text'] : '';
		$homeadvisor->additional_phones = ! empty($post['additional_phones']) ? $post['additional_phones'] : '';
		$homeadvisor->active = ! empty($post['active']) ? $post['active'] : 0;
		$homeadvisor->save();

		$user = User::find(auth()->user()->id);
		$user->update([
            'company_name' => ! empty($post['company_name']) ? $post['company_name'] : '',
            'phone' => ! empty($post['phone']) ? $post['phone'] : ''
        ]);

		return $this->message(__('Settings are successfully saved.'), 'success');
	}

    public function activate()
    {
    	$homeadvisor = Homeadvisor::firstOrNew(['users_id' => auth()->user()->id]);
    	$homeadvisor->send_request = 1;
    	$homeadvisor->text = '';
    	$homeadvisor->save();

    	$this->sendActivateEmail();

    	return $this->message(__('Your request successfully sent.'), 'success');
    }

    public function sendActivateEmail()
    {
    	$owner = User::where('owner', 1)->first();
    	$user = auth()->user();
    	$homeadvisor = Homeadvisor::where('users_id', $user->id)->first();
    	$user->ha_rep = $homeadvisor->rep;
    	$link = Link::where('users_id', $user->id)->first();
    	$user->link = $link->url;
    	$user->success = $link->success;

    	Mail::to($owner)->send(new ActivateHa($user));
    }

    public function saveLead(Request $request, $code = '')
    {
    	$data = $request->all();
    	if ( ! empty($data)) {
    		$link = Link::where('code', $code)->first();

	    	if ( ! empty($link)) {
	    		$phone = $this->findPhone($data);
	    		$client = Client::firstOrNew(['phone' => $phone, 'users_id' => $link->users_id]);
	    		$new = ! $client->exists;
	    		$client->firstname = ! empty($data['firstName']) ? $data['firstName'] : ! empty($data['first_name']) ? $data['first_name'] : '';
	    		$client->lastname = ! empty($data['lastName']) ? $data['lastName'] : ! empty($data['first_name']) ? $data['last_name'] : '';
	    		$client->phone = $phone;
	    		$client->view_phone = $phone;
	    		$client->email = ! empty($data['email']) ? $data['email'] : '';
	    		$client->source = 'HomeAdvisor';
	    		$client->save();

	    		$count = Client::where('users_id', $link->users_id)->where('source', 'HomeAdvisor')->count();
	    		$homeadvisor = Homeadvisor::where('users_id', $link->users_id)->first();
	    		$user = User::find($link->users_id);
	    		if ($new && $count == 1) {
	    			$owner = User::where('owner', 1)->first();
            		$user->rep = $homeadvisor->rep;
	    			event(new FirstLead($user, $owner));
	    		}
	    		if ( ! empty($homeadvisor->active) && ! empty($homeadvisor->text)) {
	    			$this->sendToLead($user, $client, $homeadvisor);
	    		}
	    	}

			http_response_code(200);
			echo '<success>User '.$code.'</success>';
			exit;
    	}

    	http_response_code(500);
		echo '<error>JSON POST data required</error>';
		exit;
    }

    public function sendToLead($user, $client, $homeadvisor)
    {
    	$dialog = new Dialog();
    	$dialog->users_id = $user->id;
    	$dialog->clients_id = $client->id;
    	$dialog->text = $this->createText($homeadvisor, $client, $user);
    	$dialog->my = 1;
    	$dialog->status = 2;
    	$dialog->save();
    }

    public function createText($homeadvisor, $client, $user)
    {
    	$text = $homeadvisor->text;
    	$linkPos = strpos($text, 'bit.ly/');
    	if ($linkPos !== false) {
    		$originLink = substr($text, $linkPos, 14);
    		$fakeLink = Bitly::getUrl(config('app.url').'/magic/'.$client->id.'/'.$originLink);
    		$fakeLink = str_replace('http://', '', $fakeLink);
    		$text = str_replace($originLink, $fakeLink, $homeadvisor->text);
    	}
    	return $user->company_name.': '.$text.' Txt STOP to OptOut';
    }

    public function findPhone($data)
    {
    	return ! empty($data['phonePrimary']) ? $data['phonePrimary'] : (! empty($data['primaryPhone']) ? $data['primaryPhone'] : (! empty($data['phone_primary']) ? $data['phone_primary'] : false));
    }
}
