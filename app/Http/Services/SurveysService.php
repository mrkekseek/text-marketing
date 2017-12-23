<?php

namespace App\Http\Services;

class SurveysService
{
	static public function save($data)
	{
        if (auth()->user()->surveys()->count()) {
            auth()->user()->surveys()->update($data);
        } else {
            auth()->user()->surveys()->create($data);
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
                    'message' => $client['message'],
                ]);
            }
        }
    }
}
