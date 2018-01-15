<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    protected $guarded = [];

    public function clients()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }

    public function review()
    {
        return $this->belongsTo('App\Review');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer', 'seance_id');
    }

    static public function completed()
    {
        return self::where('completed', '!=', '')->with('answers')->get();
    }

    public function scopeAlerts($query, $value, $often)
    {
        return $query->whereHas('answers', function($q) use ($value, $often) {
            $q->where('answers.value', '<=', $value)->where('answers.updated_at', '>=', \Carbon\Carbon::now()->subHours($often));
        })->where('alert', false);
    }
}
