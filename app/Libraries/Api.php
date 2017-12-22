<?php

namespace App\Libraries;

use Guzzle;

class Api
{
	static private function send($uri, $data, $method = 'POST')
    {
    	//print_r($data); die;
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
		//print_r($response->getBody()->getContents()); exit;
		$data = json_decode($response->getBody(), true);
		$data['code'] = $response->getStatusCode();
		return $data;
	}
	
	static public function company($name)
	{
		return self::send('company/name', ['name' => $name]);
	}

	static public function survey($clients, $text, $company)
	{
		$type = 'survey';
		$block = true;
		return self::send('message/send', compact('clients', 'text', 'company', 'type', 'block'));
	}
}