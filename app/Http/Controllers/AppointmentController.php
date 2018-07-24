<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\Dialog;
use App\Appointment;
use App\Jobs\SendAppointment;
use App\Libraries\ApiValidate;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function create(Request $request, User $user, Client $client)
    {
    	$data = $request->only(['text', 'date', 'schedule']);

    	if ($this->textValidate($data, $user, $client)) {
            $dialog_data = [
	            'users_id' => auth()->user()->id,
                'clients_id' => $client->id,
                'text' => $data['text'],
                'my' => true,
                'status' => 2,
            ];

            $dialog = Dialog::create($dialog_data);

    		$phones = [];
            $date = $this->getDate($data['schedule'], $data['date'], $user);
	    	$appointment = $user->appointments()->create([
	            'client_id' => $client->id,
	            'dialogs_id' => $dialog->id,
	            'text' => $data['text'],
                'schedule' => $data['schedule'],
                'date' => $date
	        ]);

	        $phones[] = ['phone' => $client->phone];

            $delay = Carbon::now()->diffInSeconds($date);

	    	SendAppointment::dispatch($appointment, $phones, $user)->delay($delay)->onQueue('texts');
	    	return $this->message(__('Message was sent'), 'success');
    	}
    }

    public function textValidate($data, $user, $client)
    {
    	if ( ! ApiValidate::companyExists($user->company_name, $user)) {
            return $this->message('This Company Name isn\'t verified');
        }

        if ( ! ApiValidate::companyVerified($user->company_name, $user)) {
            return $this->message('Company Name must be verified');
        }

        if ( ! empty($client)) {
            $length = true;
            $phones = true;
            $limit = true;

            $message = $data['text'];

            if ( ! ApiValidate::messageLength($message, $user->company_name)) {
                $length = false;
            }

            if ( ! ApiValidate::phoneFormat($client->phone)) {
                $phones = false;
            }

            if ( ! ApiValidate::underLimitAppointment($client->id)) {
                $limit = false;
            }

            if (empty($length)) {
                return $this->message('SMS Text is too long. Text will not be send');
            }

            if (empty($phones)) {
                return $this->message('Client phone numbers have wrong format. Text will not be send');
            }

            if ( ! empty($limit)) {
                return $this->message('Client phone number already received texts during last 24h. Text will not be send');
            }
        }

        $date = Carbon::now()->subHours($user->offset);

        if ( ! empty($data['schedule'])) {
            $time = $data['date'];
            $date = Carbon::create($time['year'], $time['month'], $time['date'], $time['hours'], $time['minutes'], 0, config('app.timezone'));
        }

        if (ApiValidate::underBlocking($date)) {
            return $this->message('You can\'t send texts before 9 AM.');
        }

        return 1;
    }

    public function getDate($schedule, $time, $user)
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

    public function push(Request $request, Appointment $appointment)
    {
        $data = $request->json()->all();

        $update = [
            'finish' => 0,
            'success' => 0
        ];
        foreach ($data as $client) {
            if ( ! empty($client['finish'])) {
                $update['finish'] = 1;
            }

            if ( ! empty($client['success'])) {
               $update['success'] = 1;
               $status = 1;
            }
        }

        $appointment->update($update);
        Dialog::find($appointment->dialogs_id)->update([
            'status' => $status
        ]);
    }
}
