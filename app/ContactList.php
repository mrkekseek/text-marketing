<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactList extends Model
{
    protected $guarded = [];
    protected $table = 'lists';

    public function clients()
    {
    	return $this->belongsToMany('App\Client', 'list_clients', 'lists_id', 'clients_id')->withTimestamps();
    }
}
