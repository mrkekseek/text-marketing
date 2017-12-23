<?php

namespace App\Libraries;

use Guzzle;

class Api
{
	static private function send($uri, $data, $method = 'POST')
    {
        $client = new Guzzle(['base_uri' => config('services.api.domain')]);
        $response = $client->request($method, $uri, [
            'headers' => [
				'X-Project-Token' => config('app.token'),
			],
			'http_errors' => false,
			'form_params' => $data,
		]);

        return self::response($response);
	}

	static private function response($response)
	{
		$data = json_decode($response->getBody(), true);
		$data['code'] = $response->getStatusCode();
		return $data;
	}
	
	static public function company($name)
	{
		return self::send('company/name', ['name' => $name]);
	}

	static public function review($target_id, $clients, $message, $company)
	{
		$type = 'review';
		$block = true;
		return self::send('message/send', compact('target_id', 'clients', 'message', 'company', 'type', 'block'));
	}

	static public function message($target_id, $clients, $message, $company)
	{
		$type = 'review';
		$block = false;
		return self::send('message/send', compact('target_id', 'clients', 'message', 'company', 'type', 'block'));
	}
}