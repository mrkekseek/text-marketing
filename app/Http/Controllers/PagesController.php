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
		$users_plan =  auth()->user()->plans_id == 'free-contractortexter' ? auth()->user()->paused_plans_id : auth()->user()->plans_id;
    	if (auth()->user()->type == 1) {
    		return 'users.live';
    	} else {
    		switch ($users_plan) {
    			case 'home-advisor-contractortexter': return 'ha.user';
    			case 'home-advisor-19-contractortexter': return 'ha.user';
    			case 'home-advisor-39-contractortexter': return 'ha.user';
    			case 'home-advisor-49-contractortexter': return 'ha.user';
    			case 'vonage-contractortexter': return 'vonage.user';
    			default:  return 'surveys.send';
    		}
    	}
    }

    public function menu($post = [])
    {
		$noAccess = PagesAccess::where('users_type', auth()->user()->type)->get()->pluck('code')->toArray();
		$users_plans_id = auth()->user()->plans_id;
		$users_paused_plans_id = auth()->user()->paused_plans_id;
		if (strpos($users_plans_id, 'home-advisor') !== false) {
			$users_plans_id = 'home-advisor-contractortexter';
		}
		if (strpos($users_paused_plans_id, 'home-advisor') !== false) {
			$users_paused_plans_id = 'home-advisor-contractortexter';
		}
		$users_plan =  $users_plans_id == 'free-contractortexter' ? $users_paused_plans_id : $users_plans_id;
		$plan = empty(auth()->user()->plans_id) ? 'none' : $users_plan;
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
