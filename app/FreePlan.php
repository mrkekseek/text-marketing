<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Stripe\Stripe;
use Carbon\Carbon;

class FreePlan extends Model
{
    protected $guarded = [];

    public function users()
    {
         return $this->belongsTo('App\User', 'users_id');
    }

    static public function checkLimit($user, $client)
    {
        self::checkEndOfPlan($user);
    	$free_plan_lead = $user->freePlan()->where('clients_id', $client->id)->first();
        if ($user->subscribed('Free') && empty($free_plan_lead)) {
            if ($user->freePlan()->count() == 5) {
                return false;
            }

            $free_plan_user = $user->freePlan()->where('users_id', $user->id)->first();
            if ($free_plan_user->clients_id == '0') {
                $free_plan_user->update([
                    'clients_id' => $client->id,
                ]);
            } else {
                $free_plan_user->create([
                    'users_id' => $user->id,
                    'clients_id' => $client->id,
                    'started_at' => $free_plan_user->started_at,
                    'ends_at' => $free_plan_user->ends_at,
                ]);
            }
        }
        return true;
	}
    
    static public function checkEndOfPlan($user)
    {
        $free_plan = $user->freePlan()->first();
    	if ($user->subscribed('Free') && $free_plan->ends_at < Carbon::now()) {
            $free_user = $user->freePlan()->get();
            foreach($free_user as $item) {
                $item->delete();
            }
            $free_plan->create([
				'users_id' => $user->id,
                'started_at' => Carbon::now(),
				'ends_at' => Carbon::now()->addMonth()
			]);
        }
	}
}
