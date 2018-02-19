<?php

namespace App\Http\Services;

use App\Link;

class LinksService
{
	static public function create($user)
	{
        $data = [];
        $data['users_id'] = $user->id;
        $data['code'] = self::code($user);
        $data['url'] = self::url($data['code']);
        $data['success'] = 'User '.$data['code'];

        Link::create($data);
    }

    static public function code($user)
    {
        return str_replace(['.', ',', '/', '&', '$', '=', ':', ';', '"', "'"], '_', crypt(time().$user->firstname.$user->lastname, time()));
    }
    
    static private function url($code)
    {
        return config('app.url').'/home-advisor/'.urlencode($code);
    }
}
