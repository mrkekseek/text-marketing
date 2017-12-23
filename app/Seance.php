<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function clients()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }

    public function surveys()
    {
        return $this->belongsTo('App\Survey', 'survey_id');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer', 'seance_id');
    }
}
