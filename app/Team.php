<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $guarded = [];

    public function clients()
    {
        return $this->hasMany('App\Client');
    }

    public function users()
    {
    	return $this->hasMany('App\User', 'teams_id');
    }

    public function team_leader()
    {
    	return $this->users()->where('teams_leader', 1)->first();
    }
}
