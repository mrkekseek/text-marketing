<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dialog;
use App\Client;
use Carbon\Carbon;
use App\Libraries\Api;
use App\Libraries\ApiValidate;
use App\Jobs\SendLeadText;
use Illuminate\Support\Facades\Log;


class DialogsController extends Controller
{
    public function all()
	{
		return array_values(Dialog::has('clients')->where('users_id', auth()->user()->id)->with('clients')->get()->unique('clients_id')->toArray());
	}

	public function info($id = false)
	{
        Dialog::where('clients_id', $id)->where('users_id', auth()->user()->id)->update(['new' => 0]);
		return Dialog::where('clients_id', $id)->where('users_id', auth()->user()->id)->get();
	}

	public function create(Request $request, Client $client)
	{
		$data = $request->only(['text', 'time']);

		if ($this->textValidate($data, $client, $request)) {
            $dialog = auth()->user()->dialogs()->create([
                'clients_id' => $client->id,
                'text' => $data['text'],
                'my' => true,
                'status' => 2,
            ]);
            $phones = [];
            $phones[] = ['phone' => $client->phone];

            SendLeadText::dispatch($dialog, $phones, $dialog->text, auth()->user())->onQueue('texts');

			$this->message(__('Message was send'), 'success');
			return $dialog;
		}
	}

	public function textValidate($data, $client)
    {
        if ( ! ApiValidate::companyExists(auth()->user()->company_name, auth()->user())) {
            return $this->message('This Company Name isn\'t verified');
        }

        if ( ! ApiValidate::companyVerified(auth()->user()->company_name, auth()->user())) {
            return $this->message('Company Name must be verified');
        }

        $text = trim($data['text']);
        if ( ! ApiValidate::messageSymbols($text)) {
            return $this->message('SMS Text contains forbidden characters');
        }

        if ( ! empty($client)) {
            $length = true;
            $phones = true;
            $limit = true;
            
            $message = $text;

            if ( ! ApiValidate::messageLength($message, auth()->user()->company_name)) {
                $length = false;
            }

            if ( ! ApiValidate::phoneFormat($client->phone)) {
                $phones = false;
            }

            if (ApiValidate::underLimitDialog($client->id)) {
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

        $date = (object)$data['time'];
        if (ApiValidate::underBlocking($date)) {
            return $this->message('You can\'t send texts before 9 AM. You can try to use Schedule Send');
        }

        return 1;
    }

    public function inbox(Request $request, Dialog $dialog)
    {
        $data = $request->only(['CONTENTS']);
        $dialog = $dialog->replicate();
        $dialog->text = $data['CONTENTS'];
        $dialog->new = true;
        $dialog->status = 1;
        $dialog->my = false;
        $dialog->save();
    }
}
