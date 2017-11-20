<?php

namespace App\Http\Controllers;

use App\Page;
use App\PagesAccess;
use App\PagesMenu;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function defaultPage($post = [])
    {
    	return auth()->user()->type == 1 ? 'users.list' : 'ha.user';
    }

    public function menu($post = [])
    {
    	$noAccess = PagesAccess::where('users_type', auth()->user()->type)->get()->pluck('code')->toArray();
    	$menu = PagesMenu::whereNotIn('pages_code', $noAccess)->orderBy('pos')->get();
    	$codes = $menu->pluck('pages_code')->toArray();
    	$pages = Page::whereIn('code', $codes)->get();

    	$temp = [];
    	foreach ($pages as $page) {
    		$temp[$page['code']] = $page;
    	}

    	$items = [];
    	foreach ($menu as $parent) {
			if (empty($parent['parents_code']))
			{
				$row = $temp[$parent['pages_code']]->toArray();
				$row['main'] = $parent['main'];
				$row['pages'] = [];

				foreach ($menu as $child) {
					if ($parent['pages_code'] == $child['parents_code']) {
						$val = $temp[$child['pages_code']]->toArray();
						$val['parents_code'] = $child['parents_code'];
						$val['main'] = $child['main'];

						$row['pages'][] = $val;
					}
				}
				$items[] = $row;
			}
		}

		return $items;
    }
}
