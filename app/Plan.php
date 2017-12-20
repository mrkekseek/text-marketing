<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['plans_id'];

    static public function findById($plans_id)
    {
        return self::where('plans_id', $plans_id)->first();
    }

    public function users()
    {
    	return $this->belongsTo('App\User', 'plans_id', 'plans_id');
    }
}
