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
}
