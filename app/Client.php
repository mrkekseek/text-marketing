<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];

    public function lists()
    {
    	return $this->belongsToMany('App\ContactList', 'list_clients', 'clients_id', 'lists_id')->withTimestamps();
    }

    public function dialogs()
    {
    	return $this->hasMany('App\Dialog', 'clients_id');
    }

    public function seances()
    {
    	return $this->hasMany('App\Seance')->with('answers');
    }
}
