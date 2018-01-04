<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function save(Request $request)
    {
    	$path = '';
		if ($request->file) {
			$path = $request->file->store('public/upload');
		}
		return $path = str_replace('public', 'storage', $path);
    }
}
