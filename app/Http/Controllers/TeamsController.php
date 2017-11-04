<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function get($post = [])
	{
		return Team::all();
	}

	public function save($post = [])
	{
		$team = Team::firstOrNew(['id' => empty($post['id']) ? 0 : $post['id']]);
		$team->name = $post['name'];
		$team->save();

		return $this->message(__('Team was successfully saved'), 'success');
	}

	public function remove($post = [])
	{
		Team::destroy($post['id']);
		return $this->message(__('Team was successfully removed'), 'success');
	}

	public function companies($post = [])
	{
		return [];
	}
}
