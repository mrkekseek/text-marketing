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
        return $this->hasOne('App\Homeadvisor', 'users_id');
    }

    public function lists()
    {
         return $this->hasMany('App\ContactList', 'users_id');
    }

    public function surveys()
    {
        return $this->hasOne('App\Survey', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review', 'user_id');
    }

    public function urls()
    {
        return $this->hasMany('App\Url', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Message', 'user_id');
    }

    public function dialogs()
    {
        return $this->hasMany('App\Dialog', 'users_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function($user) {
            $user->defaultUrls();
        });

        static::deleting(function($user) {
            //
        });
    }

    public function defaultUrls()
    {
        $urls = [
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

        foreach ($urls as $url) {
            $this->urls()->create($url);
        }
    }

    public function scopePartners($query)
    {
        return $query->where('teams_id', $this->teams_id)->where('teams_leader', false);
    }
}
