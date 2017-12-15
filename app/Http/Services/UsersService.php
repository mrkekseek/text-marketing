<?php

namespace App\Http\Services;

use App\Team;
use App\Plan;
use Carbon\Carbon;

class UsersService
{
	static public function createTeam($data)
	{
        $name = [$data['firstname']];
        if ( ! empty($data['lastname'])) {
            $name[] = $data['lastname'];
        }

        $team = Team::create(['name' => implode(' ', $name)]);
        return $team->id;
    }
    
    static public function password($password)
    {
        return ! empty($password) ? bcrypt($password) : null;
    }

	static public function phoneToNumber($data)
	{
		return ! empty($data['view_phone']) ? str_replace(['-', '.', ' ', '(', ')'], '', $data['view_phone']) : '';
    }
    
    static public function trialEndsAt($plans_id)
    {
        $plan = Plan::findById($plans_id);
        return Carbon::now()->addDays($plan ? $plan->trial : 0);
    }
}
