<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable, Billable;

    protected $guarded = [];
    protected $dates = [
        'trial_ends_at'
    ];

    public function username()
    {
        return 'email';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function clients()
    {
        return $this->hasMany('App\Client', 'users_id');
    }

    public function surveys()
    {
        return $this->hasMany('App\Survey', 'users_id');
    }
}
