<?php

namespace App\Http\Controllers;

use App\HomeAdvisor;
use Illuminate\Http\Request;

class HomeAdvisorController extends Controller
{
    public function linksSave($post = [])
	{
		return true;
	}

	public function getLinks()
	{
		return HomeAdvisor::all();
	}
}
