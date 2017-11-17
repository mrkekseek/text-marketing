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
				$row = $temp[$parent['pages_code']];
				$row['main'] = $parent['main'];

				foreach ($menu as $child) {
					if ($parent['pages_code'] == $child['parents_code']) {
						$temp[$child['pages_code']]['parents_code'] = $child['parents_code'];
						$temp[$child['pages_code']]['main'] = $child['main'];
						//$row['pages'][] = $temp[$child['pages_code']];
						//$row['pages'][] = $child;
						//print_r($child);
					}
				}
				$items[] = $row;
			}
		}

		return $items;

    	/*$items = [];
		$users_type = $this->users->type();
		if ( ! empty($users_type))
		{
			$not_access = [];
			$this->db->where('users_type', $users_type);
			$result = $this->db->get('pages_access')->result_array();
			foreach ($result as $row)
			{
				$not_access[] = $row['pages_code'];
			}
			
			$menu = [];
			$this->db->order_by('pages_pos', 'asc');
			$result = $this->db->get('pages_menu')->result_array();
			foreach ($result as $row)
			{
				if ( ! in_array($row['pages_code'], $not_in_project) && ! in_array($row['pages_code'], $not_access))
				{
					$menu[] = $row;
					$pages_codes[] = $row['pages_code'];
				}
			}

			if ( ! empty($pages_codes))
			{
				$temp = [];
				$this->db->where_in('pages_code', $pages_codes);
				$pages = $this->db->get('pages_pages')->result_array();
				foreach ($menu as $key => $row)
				{
					foreach ($pages as $page)
					{
						if ($page['pages_code'] == $row['pages_code'])
						{
							$temp[$page['pages_code']] = $page;
						}
					}
				}

				foreach ($menu as $key => $val)
				{
					if (empty($val['parents_code']))
					{
						$row = $temp[$val['pages_code']];
						$row['pages_main'] = $val['pages_main'];

						foreach ($menu as $item)
						{
							if ($row['pages_code'] == $item['parents_code'])
							{
								$temp[$item['pages_code']]['parents_code'] = $item['parents_code'];
								$temp[$item['pages_code']]['pages_main'] = $item['pages_main'];
								$row['pages'][] = $temp[$item['pages_code']];
							}
						}

						$items[] = $row;
					}
				}
			}
		}

		return $items;*/
    }
}
