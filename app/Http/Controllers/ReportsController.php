<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\Homeadvisor;
use App\Libraries\Api;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function get(Request $request)
    {
        $data = $request->only(['type', 'phone', 'date', 'user']);
        $ids = $this->getIds($data);
        if ( ! empty($ids)) {
            $check = false;
            foreach ($ids as $row) {
                if ( ! empty($row)) {
                    $check = true;
                }
            }

            if (empty($check)) {
                return [];
            }
        }

        $response = Api::reports($data['type'], $data['phone'], $data['date'], $ids);
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

    public function getIds($data)
    {
        $ids = [];
        if ( ! empty($data['user']['id']))
        {
            $user_id = $data['user']['id'];
            if (empty($data['type']) || $data['type'] == 'dialog') {
                $ids['dialog'] = User::find($user_id)->dialogs()->whereDate('created_at', Carbon::parse($data['date'])->toDateString())->get()->pluck('id')->toArray();
            }

            if (empty($data['type']) || $data['type'] == 'review') {
                $ids['review'] = User::find($user_id)->reviews()->whereDate('created_at', Carbon::parse($data['date'])->toDateString())->get()->pluck('id')->toArray();
            }

            if (empty($data['type']) || $data['type'] == 'alert') {
                $ids['alert'] = User::find($user_id)->alerts()->whereDate('created_at', Carbon::parse($data['date'])->toDateString())->get()->pluck('id')->toArray();
            }
        }

        return $ids;
    }
}
