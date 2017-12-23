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
		
		GetSocialIds::dispatch($url)->onQueue('socials');

		$this->message('Review Site was successfully saved', 'success');
		return $url;
	}

	public function update(UrlsCreateRequest $request, Url $url)
	{
		$data = $request->only(['name', 'url', 'active']);
		$data = array_filter($data, 'strlen');
		$url->update($data);

		$this->message('Review Site was successfully saved', 'success');
		return $url;
	}

	public function remove(Url $url)
	{
		$url->delete();
		return $this->message('Review Site was successfully removed', 'success');
	}
}
