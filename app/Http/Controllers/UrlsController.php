<?php

namespace App\Http\Controllers;

use App\SocialUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UrlsController extends Controller
{
    public function all()
	{
		return SocialUrl::where('users_id', Auth::user()->id)->get();
	}

	public function save($id = false, $post = [])
	{
		SocialUrl::where('default', 0)->where('users_id', Auth::user()->id)->delete();

		foreach ($post as $row) {
			if ( ! empty($row)) {
				$url = SocialUrl::firstOrNew(['id' => empty($row['id']) ? 0 : $row['id']]);
				$url->users_id = Auth::user()->id;
				$url->name = $row['name'];
				$url->url = ! empty($row['url']) ? $row['url'] : '';
				$url->active = ! empty($row['active']) ? $row['active'] : 0;
				$url->save();
			}
		}
		return $this->message(__('Social Profile Pages were successfully saved'), 'success');
	}
}
