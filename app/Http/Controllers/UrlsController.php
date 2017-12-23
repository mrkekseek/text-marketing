<?php

namespace App\Http\Controllers;

use File;
use App\Url;
use App\Http\Requests\UrlsCreateRequest;
use App\Jobs\GetSocialIds;
use Illuminate\Http\Request;

class UrlsController extends Controller
{
    public function all()
	{
		return auth()->user()->urls()->get();
	}

	public function create(UrlsCreateRequest $request)
	{
		$data = $request->only(['name', 'url', 'active']);
		$data = array_filter($data, 'strlen');
		$url = auth()->user()->urls()->create($data);
		
		$job = (new GetSocialIds($url))->onQueue('socials');
        $this->dispatch($job);

		$this->message(__('Review Site was successfully saved'), 'success');
		return $url;
	}

	public function remove($id = false, $post = [])
	{
		SocialUrl::destroy($id);
		File::delete('img/icon_url_'.$id.'.ico');
		return $this->message(__('Url was successfully removed'), 'success');
	}
}
