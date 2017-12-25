<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\User;

class SurveysService
{
	static public function save($data, $user)
	{
        $user = empty($user) ? auth()->user() : $user;
        if ($user->surveys()->count()) {
            $user->surveys()->update($data);
        } else {
            $user->surveys()->create($data);
        }
    }

    static public function seance($review, $clients)
	{
        foreach ($review->seances as $seance) {
            if ( ! empty($clients[$seance->clients->phone])) {
                $client = $clients[$seance->clients->phone];
                $seance->update([
                    'finish' => $client['finish'],
                    'success' => $client['success'],
                    'message' => ! empty($client['message']) ? $client['message'] : '',
                ]);
            }
        }
    }

    static public function alertsDelay($often)
    {
        $date = Carbon::now();
        switch ($often) {
            case 1:
                $date = $date->addHours(1)->subMinutes(Carbon::now()->minute);
                break;
            case 2:
                $date = $date->addHours($date->hour % 2 ? 1 : 2)->subMinutes(Carbon::now()->minute);
                break;
            case 3:
                $date = $date->addHours($date->hour % 3 ? (3 - $date->hour % 3) : 3)->subMinutes(Carbon::now()->minute);
                break;
            case 24:
                $date = Carbon::tomorrow()->addHours(9);
                break;
            case 48:
                $date = Carbon::today()->addDays($date->dayOfYear % 2 ? 1 : 2)->addHours(9);
                break;
            case 168:
                $date = $date->next(Carbon::MONDAY)->addHours(9);
                break;
        }

        return Carbon::now()->diffInSeconds($date);
    }
}
