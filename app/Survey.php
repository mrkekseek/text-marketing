<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $guarded = [];

    static public function findDefault()
    {
        return self::where('user_id', 0)->first();
    }
}
