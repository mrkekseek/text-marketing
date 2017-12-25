<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dialog;
use App\Client;
use App\Libraries\Api;
use App\Libraries\ApiValidate;


class DialogsController extends Controller
{
    public function all()
	{
		return Dialog::has('clients')->where('users_id', auth()->user()->id)->with('clients')->get()->unique('clients_id')->toArray();
	}

	public function info($id = false)
	{
		return Dialog::where('clients_id', $id)->where('users_id', auth()->user()->id)->get();
	}

	public function create(Request $request, Client $client)
	{
		$data = $request->only(['text']);
		if ($this->textValidate($data['text'], $client)) {
			$data['users_id'] = auth()->user()->id;
			$data['clients_id'] = $client->id; 
			$data['text'] = auth()->user()->company_name.': '.$data['text'];
			$data['my'] = 1;
			$data['status'] = 2;
			$dialog = Dialog::create($data);

			$this->message(__('Message was send'), 'success');
			return $dialog;
		}
	}

	public function textValidate($text, $client)
    {
        if ( ! ApiValidate::companyExists(auth()->user()->company_name, auth()->user())) {
            return $this->message('This Company Name isn\'t verified');
        }

        if ( ! ApiValidate::companyVerified(auth()->user()->company_name, auth()->user())) {
            return $this->message('Company Name must be verified');
        }

        $text = trim($text);
        if ( ! ApiValidate::messageSymbols($text)) {
            return $this->message('SMS Text contains forbidden characters');
        }

        if ( ! empty($clients)) {
            $length = true;
            $phones = true;
            $limit = true;
            
            $message = $text;

            if ( ! ApiValidate::messageLength($message, $data['company'])) {
                $length = false;
            }

            if ( ! ApiValidate::phoneFormat($client['phone'])) {
                $phones = false;
            }

            if (ApiValidate::underLimitMarketing($client['id'])) {
                $limit = false;
            }

            if (empty($length)) {
                return $this->message('SMS Text is too long. Text will not be send');
            }

            if (empty($phones)) {
                return $this->message('Some client\'s phone numbers have wrong format. Text will not be send');
            }

            if (empty($limit)) {
                return $this->message('Some client\'s phone numbers already received texts during last 24h. Text will not be send');
            }
        }

        if (ApiValidate::underBlocking()) {
            return $this->message('You can\'t send texts before 9 AM. You can try to use Schedule Send');
        }

        return 1;
    }
}
