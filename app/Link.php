<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    public function teams()
    {
        return $this->belongsTo('App\Team');
    }
}
