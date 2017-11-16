<?php

namespace App\Http\Controllers;

use App\Homeadvisor;
use Illuminate\Http\Request;

class HomeadvisorController extends Controller
{
    public function saveLink($id = false, $post = [])
	{
		$homeAdvisor = new Homeadvisor;
		$homeAdvisor->teams_id = $post['teams_id'];
		$homeAdvisor->links_code = $this->linksCodeGenerate($post['firstname'].$post['lastname']);
		$homeAdvisor->firstname = $post['firstname'];
		$homeAdvisor->lastname = $post['lastname'];
		$homeAdvisor->phone = $post['phone'];
		$homeAdvisor->link_for_ha = $this->linkForHAGenerate($homeAdvisor->links_code);
		$homeAdvisor->success_string = 'User '.$homeAdvisor->links_code;
		$homeAdvisor->save();

		return $homeAdvisor;
	}

	public function all()
	{
		return HomeAdvisor::all();
	}

	public function remove($id = false)
	{
		Homeadvisor::destroy($id);
		return $this->message(__('Link was successfully removed'), 'success');
	}

	public function linkForHAGenerate($code)
	{
		return config('url').'/home-advisor/'.urlencode($code).'/';
	}

	public function linksCodeGenerate($str)
	{
		return str_replace(['.', ',', '/', '&', '$', '=', ':', ';', '"', "'"], '_', crypt(time().$str, time()));
	}
}
