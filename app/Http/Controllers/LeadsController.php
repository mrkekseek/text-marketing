<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Lead;
use App\Homeadvisor;
use App\Libraries\Api;
use Carbon\Carbon;

class LeadsController extends Controller
{
    public function get(Request $request)
    {
        $response = Lead::whereDate('created_at', Carbon::parse($request->date)->toDateString());

        if ( ! empty($request->user)) {
            $user = $request->user;
            $response->where('user_id', $user);
        }

        $data = $response->get();
        foreach($data as $lead) {
            $info = json_decode($lead->data, true);
            $lead->firstName = $this->firstName($info);
            $lead->lastName = $this->lastName($info);
            $lead->primary_phone = $this->phone($info);
            $lead->user = $lead->user()->first();
            Carbon::setToStringFormat('F dS g:i A');
			$lead->created_at_string = $lead->created_at->__toString();
			Carbon::resetToStringFormat();
        }
        return $data;
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
}
