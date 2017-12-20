<?php

namespace App\Http\Controllers;

use App\SocialUrl;
use Illuminate\Http\Request;
use File;
use App\Jobs\GetSocialIds;

class UrlsController extends Controller
{
    public function all()
	{
		return SocialUrl::where('users_id', auth()->user()->id)->get();
	}

	public function save(Request $request, $id = false)
	{
		$url = SocialUrl::firstOrNew(['id' => empty($id) ? 0 : $id]);
		$url->users_id = auth()->user()->id;
		$url->name = $post['name'];
		$url->url = $post['url'];
		$url->active = ! empty($post['active']) ? $post['active'] : 0;
		$url->save();

		$file = 'img/icon_url_'.$url->id.'.ico';
		copy('https://www.google.com/s2/favicons?domain='.$url->url, $file);
		$url->icon = $file;
		$this->message(__('Social Profile Pages were successfully saved'), 'success');

		$job = (new GetSocialIds($url))->onQueue('socials');
        $this->dispatch($job);

		return $url;
	}

	public function remove($id = false, $post = [])
	{
		SocialUrl::destroy($id);
		File::delete('img/icon_url_'.$id.'.ico');
		return $this->message(__('Url was successfully removed'), 'success');
	}
}
