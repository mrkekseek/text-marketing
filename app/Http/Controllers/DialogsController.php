<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dialog;
use App\Client;
use App\User;
use App\Alert;
use Carbon\Carbon;
use App\Libraries\Api;
use App\Libraries\ApiValidate;
use App\Jobs\SendLeadText;
use App\Jobs\SendAlertClick;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use DivArt\ShortLink\Facades\ShortLink;
use App\Mail\SendAlertEmail;


class DialogsController extends Controller
{
    public function all()
	{
		return array_values(Dialog::has('clients')->where('users_id', auth()->user()->id)->with('clients')->orderBy('created_at', 'desc')->get()->each(function($item, $key) {
            $offset = auth()->user()->offset;
            Carbon::setToStringFormat('F dS g:i A');
			$item->clients->created_at_string = $item->clients->created_at->subHour($offset)->__toString();
            Carbon::resetToStringFormat();
			return $item;
        })->unique('clients_id')->toArray());
	}

	public function info($id = false)
	{
        Dialog::where('clients_id', $id)->where('users_id', auth()->user()->id)->update(['new' => 0]);
		return Dialog::where('clients_id', $id)->where('users_id', auth()->user()->id)->with('clients')->orderBy('created_at', 'asc')->get()->each(function($item, $key) {
            $offset = auth()->user()->offset;
			Carbon::setToStringFormat('F dS g:i A');
			$item->created_at_string = $item->created_at->subHour($offset)->__toString();
            Carbon::resetToStringFormat();
			return $item;
        });
	}

	public function create(Request $request, Client $client)
	{
		$data = $request->only(['text', 'time']);

		if ($this->textValidate($data, $client, $request, false)) {
            $dialog = auth()->user()->dialogs()->create([
                'clients_id' => $client->id,
                'text' => $data['text'],
                'my' => true,
                'status' => 2,
            ]);
            $phones = [];
            $phones[] = ['phone' => $client->phone];

            SendLeadText::dispatch($dialog, $phones, auth()->user())->onQueue('texts');

			$this->message(__('Message was send'), 'success');
			return $dialog;
		}
	}

	public function textValidate($data, $client, $request, $block = true)
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

    public function push(Request $request, Dialog $dialog)
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

        $dialog->update([
            'status' => $status
        ]);
    }

    public function inbox(Request $request, Dialog $dialog)
    {
        $data = $request->only(['CONTENTS']);
        $this->saveLog($data, 'INBOX');
        $this->saveLog($dialog, 'INBOX DIALOG');
        $dialog->update(['reply' => 1]);
        $dialog = $dialog->replicate();
        $dialog->text = $data['CONTENTS'];
        $dialog->new = true;
        $dialog->status = 1;
        $dialog->my = false;
        $dialog->reply = 0;
        $dialog->clicked = 0;
        $dialog->save();

        $user = User::find($dialog->users_id);
        
        $magicLink = false;
        if ( ! empty($user->phone) || ! empty($user->homeadvisors->additional_phones) || ! empty($user->homeadvisors->emails)) {
            $magicLink = $this->getMagicLink($user->id, $dialog->clients_id);
        }

        if ( ! empty($user->phone) || ! empty($user->homeadvisors->additional_phones)) {
            $this->sendAlert($user, $magicLink);
        }
        
        if ( ! empty($user->homeadvisors->emails)) {
            $this->sendAlertEmail($user, $magicLink);
        }
    }

    private function getMagicLink($id, $clients_id)
    {
        $link = ShortLink::bitly(config('app.url').'/magic/inbox/'.$id.'/'.$clients_id, false);
        return $link;
    }

    public function saveLog($data, $source)
    {
        if ( ! file_exists('logs')) {
            mkdir('logs', 0777);
        }
        file_put_contents('logs/logger.txt', date('[Y-m-d H:i:s] ').$source.': '.print_r($data, true).PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public function sendAlert($user, $link)
    {
        $phones = [];
        $temp = [];

        $text = 'Hi '.$user->firstname .', a lead just texted you a reply. Please click '.$link.' to see it and reply if you like - thanks!';

        if ( ! empty($user->phone)) {
            $phones[]['phone'] = $user->phone;
            $temp[] = $user->phone;
        }

        if ( ! empty($user->homeadvisors->additional_phones)) {
            $numbers = explode(',', $user->homeadvisors->additional_phones);
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
            $delay = Carbon::now()->addMinutes(10);
            $dialog = Dialog::where('users_id', $user->id)->first();
            SendAlertClick::dispatch($alert, $phones, $text, $user)->onQueue('texts');
            SendAlertClick::dispatch($alert, $phones, $text, $user, $dialog->reply_viewed)->delay($delay)->onQueue('texts');
        }
    }

    public function sendAlertEmail($user, $link)
    {
        $temp = [];
        
        if ( ! empty($user->homeadvisors->emails)) {
            $emails = explode(',', $user->homeadvisors->emails);

            foreach ($emails as $email) {
                $temp[] = $email;
            }
        }
        
        if ( ! empty($temp)) {
            $message = (new SendAlertEmail($user->firstname, 'https://'.$link))
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
}
