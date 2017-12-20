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
    	if (auth()->user()->type == 1) {
    		return 'users.list';
    	} else {
    		switch (auth()->user()->plans_id) {
    			case 'home-advisor-contractortexter': return 'ha.user';
    			default:  return 'surveys.send';
    		}
    	}
    }

    public function menu($post = [])
    {
		$noAccess = PagesAccess::where('users_type', auth()->user()->type)->get()->pluck('code')->toArray();
		$plan = empty(auth()->user()->plans_id) ? 'none' : auth()->user()->plans_id;
		$menu = PagesMenu::whereNotIn('pages_code', $noAccess)->where('plans', $plan)->orderBy('pos')->get();
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
