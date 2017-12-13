<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dialog extends Model
{
	protected $guarded = [];

    public function clients()
    {
    	return $this->belongsTo('App\Client', 'clients_id');
    }
}
