<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];
    protected $dates = ['date', 'finish_date', 'token'];

    public function texts()
    {
    	return $this->hasMany('App\Text', 'message_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
