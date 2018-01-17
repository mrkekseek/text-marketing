<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function team()
    {
        return $this->belongsTo('App\Team');
    }

    public function dialogsClicked()
    {
        $date = Carbon::now()->subWeek();
        return $this->hasMany('App\Dialog', 'clients_id')->where('clicked', 1)->where('created_at', '>', $date);
    }

    public function dialogsReply()
    {
        $date = Carbon::now()->subWeek();
        return $this->hasMany('App\Dialog', 'clients_id')->where('reply', 1)->where('created_at', '>', $date);
    }
}
