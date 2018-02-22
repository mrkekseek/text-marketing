<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\Homeadvisor;
use App\Libraries\Api;

class ReportsController extends Controller
{
    public function get(Request $request)
    {
        $data = $request->only(['type', 'phone', 'date']);
        $response = Api::reports($data['type'], $data['phone'], $data['date']);
        if ($response['code'] == 200) {
            return $response['data'];
        } else {
            return [];
        }
    }

    public function phones()
    {
        $phones = User::where('phone', '<>', '')->get()->pluck('phone')->toArray();
        $phones = array_merge($phones, Client::where('phone', '<>', '')->get()->pluck('phone')->toArray());
        $ha = Homeadvisor::where('additional_phones', '<>', '')->get()->pluck('phone')->toArray();
        foreach($ha as $row) {
            $aps = array_values(array_diff(explode(',', $row), ['']));
            $phones = array_merge($phones, $aps);
        }
        $phones = array_unique($phones);
        sort($phones);
        return $phones;
    }
}
