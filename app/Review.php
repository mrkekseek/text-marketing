<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = [];

    public function seances()
    {
        return $this->hasMany('App\Seance', 'review_id');
    }

    public function survey()
    {
        return $this->belongsTo('App\Survey');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
