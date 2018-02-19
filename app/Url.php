<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
	protected $guarded = [];

	protected $attributes = [
		'url' => '',
	];
}
