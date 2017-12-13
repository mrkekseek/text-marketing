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

    public function lists()
    {
         return $this->hasMany('App\ContactList', 'users_id');
    }

    public function surveys()
    {
        return $this->hasMany('App\Survey', 'users_id');
    }

    public function links()
    {
        return $this->hasMany('App\SocialUrl', 'users_id');
    }

    public function defaultUrls()
    {
        $socialsUrls = [
            [
                'name' => 'Facebook',
                'default' => 1
            ], [
               'name' => 'Google',
                'default' => 1 
            ], [
                'name' => 'Yelp',
                'default' => 1
            ]
        ];

        foreach ($socialsUrls as $row) {
            $this->links()->create($row);
        }
    }
}
