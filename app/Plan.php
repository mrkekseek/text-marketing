<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['plans_id'];

    public function users()
    {
    	return $this->belongsTo('App\User', 'plans_id', 'plans_id');
    }
}
