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

    public function all()
	{
		return Team::all();
	}

	public function save($id = false, $post = [])
	{
		$team = Team::firstOrNew(['id' => empty($id) ? 0 : $id]);
		$team->name = $post['name'];
		$team->save();

		return $this->message(__('Team was successfully saved'), 'success');
	}

	public function remove($id)
	{
		Team::destroy($id);
		return $this->message(__('Team was successfully removed'), 'success');
	}

	public function companies($post = [])
	{
		return [];
	}
}
