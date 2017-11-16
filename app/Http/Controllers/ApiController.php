<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function run($sunit, $sid = false, $smethod = false)
    {
    	if ($sunit == 'auth' || $sunit != 'auth' && auth()->check()) {
            $id = $this->id($sid);
            $data = $this->data($smethod);
	    	$method = $this->method($sid, $smethod);

		    $controller = app()->make('\App\Http\Controllers\\'.ucfirst($sunit).'Controller');
		    $response = $controller->callAction($method, [
                'id' => $id,
                'data' => $data
            ]);
            return json_encode($response, JSON_NUMERIC_CHECK);
    	}
    }

    public function id($id)
    {
        if ($id && is_numeric($id)) {
            return $id;
        }

        return false;
    }

    public function data($smethod)
    {
        $data = request()->all();
        if ($smethod && in_array(request()->method(), ['POST', 'DELETE'])) {
            $data['_checked'] = request()->method() == 'POST';
        }

        return $data;
    }

    public function method($sid, $smethod)
    {
        $method = $smethod;
        if (empty($sid)) {
            return 'all';
        } else {
            if (is_numeric($sid)) {
                if (empty($smethod)) {
                    switch (request()->method()) {
                        case 'GET':
                            $method = 'info';
                            break;
                        case 'POST':
                            $method = 'save';
                            break;
                        case 'DELETE':
                            $method = 'remove';
                            break;
                        default:
                            $method = $sid;
                            break;
                    }
                }
            } else {
                $method = $sid; 
            }
        }

        return $method;
    }
}
