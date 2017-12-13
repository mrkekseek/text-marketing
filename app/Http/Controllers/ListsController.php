<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContactList;
use App\User;

class ListsController extends Controller
{
    public function all()
	{
		$user = auth()->user();
		$user = User::with('lists.clients')->find(auth()->user()->id);

		$lists = $user->lists;

		/*foreach ($lists as $key => $row) {
			$lists[$key]['clients'] = [];
		}*/
		return $lists;
	}

	public function save($id = false, $post = [])
	{
		$list = ContactList::firstOrNew(['id' => empty($id) ? 0 : $id]);
		$list->users_id = auth()->user()->id;
		$list->name = $post['name'];
		$list->save();
		return $list->id;
	}

	public function remove($id = false)
	{
		$list = ContactList::find($id);
		$list->delete();
		$list->clients()->detach();
		return $this->message(__('List was successfully removed'), 'success');
	}
}
