<?php

namespace App\Http\Controllers;

use App\Link;
use Illuminate\Http\Request;

class LinksController extends Controller
{
    public function all()
	{
		return Link::with('user.homeadvisors')->get();
    }
    
    public function save($id = false, $post = [])
	{
		$link = new Link();
		$link->teams_id = $post['teams_id'];
		$link->code = empty($link->id) ? $this->code($post) : $link->code;
		$link->firstname = $post['firstname'];
		$link->lastname = $post['lastname'];
		$link->phone = $post['phone'];
		$link->url = empty($link->id) ? $this->url($link->code) : $link->url;
		$link->success = 'User '.$link->code;
		$link->save();

		return $this->message(__('Link was successfully saved'), 'success');
    }
    
    public function code($post = [])
	{
		return str_replace(['.', ',', '/', '&', '$', '=', ':', ';', '"', "'"], '_', crypt(time().$post['firstname'].$post['lastname'], time()));
    }
    
    public function url($code)
	{
		return config('app.url').'/home-advisor/'.urlencode($code).'/';
	}

	public function remove($id = false)
	{
		Link::destroy($id);
		return $this->message(__('Link was successfully removed'), 'success');
	}
}
