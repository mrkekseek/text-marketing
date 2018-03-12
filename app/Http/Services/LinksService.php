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

        $code_exists = Link::where('code', $data['code'])->exists();

        while ($code_exists) {
            $data['code'] = self::code($user, true);
            $code_exists = Link::where('code', $data['code'])->exists();
        }

        Link::create($data);
    }

    static public function code($user, $extra_crypt = false)
    {
        $extra_key = '';
        if ($extra_crypt) {
            mt_srand();
            $extra_key = mt_rand();
        }

        return str_replace(['.', ',', '/', '&', '$', '=', ':', ';', '"', "'"], '_', crypt($extra_key.time().$user->firstname.$user->lastname, time()));
    }
    
    static private function url($code)
    {
        return config('app.url').'/home-advisor/'.urlencode($code);
    }
}
