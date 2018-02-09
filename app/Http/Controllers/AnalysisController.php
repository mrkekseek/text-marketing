<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalysisController extends Controller
{
    public function all()
    {
    	return auth()->user()->reviews()->whereHas('seances', function($q) {
            $q->where('completed', '!=', '');
            $q->whereNotNull('completed');
        })->with(['seances' => function($q){
            $q->whereNotNull('completed');
        }, 'seances.answers', 'seances.clients'])->withCount(['seances' => function($q){
            $q->whereNotNull('completed');
        }])->get();
    }

    public function calendar(Request $request)
    {
        $data = $request->only(['date']);

        $from = Carbon::create($data['date']['year'], $data['date']['month'], $data['date']['date'], 0, 0, 0);
        $to = Carbon::create($data['date']['year'], $data['date']['month'], $data['date']['date'], 23, 59, 59);

        return auth()->user()->reviews()->whereHas('seances', function($q) use ($from, $to) {
            $q->whereNotNull('completed');
            $q->where('completed', '>', $from);
            $q->where('completed', '<', $to);
        })->with(['seances' => function($q){
            $q->whereNotNull('completed');
            $q->with('clients');
            $q->with('answers');
        },'seances.answers', 'seances.clients'])->get();
    }
}
