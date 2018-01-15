<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Seance;
use App\User;
use App\Review;

class AnalysisController extends Controller
{
    public function all()
    {
    	$result = [];
    	foreach (auth()->user()->reviews as $review) {
    		$seance = $review->seances->where('completed', '!=', '')->first();
    		if ( ! empty($seance)) {
    			$seance['answers'] = $seance->answers;
    			$result[] = $seance;
    		}
    	}
    	return $result;
    }
}
