<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable, Billable;

    protected $hidden = ['password'];
    protected $guarded = [];
    protected $dates = ['trial_ends_at'];

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

    static public function allUsers()
    {
        return self::where('type', '2')->where('teams_leader', '1')->with('teams')->get();
    }

    public function teams()
    {
        return $this->belongsTo('App\Team', 'teams_id');
    }

    public function links()
    {
        return $this->hasOne('App\Link', 'users_id');
    }

    public function homeadvisors()
    {
        return $this->hasOne('App\HomeAdvisor', 'users_id');
    }

    public function lists()
    {
         return $this->hasMany('App\ContactList', 'users_id');
    }

    public function surveys()
    {
        return $this->hasOne('App\Survey', 'user_id');
    }

    public function seances()
    {
        return $this->hasOne('App\Seance', 'user_id');
    }

    public function socials()
    {
        return $this->hasMany('App\SocialUrl', 'users_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Message', 'user_id');
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
            $this->socials()->create($row);
        }
    }
}
