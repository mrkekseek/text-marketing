<?php

namespace App\Libraries;

use Guzzle;

class Api
{
	static private function send($uri, $data, $method = 'POST')
    {
		$data['env'] = env('APP_ENV');
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
		//return $response->getBody()->getContents();
		$data = json_decode($response->getBody(), true);
		$data['code'] = $response->getStatusCode();
		return $data;
	}
	
	static public function company($name)
	{
		return self::send('company/name', ['name' => $name]);
	}

	static public function review($target_id, $clients, $message, $company, $offset)
	{
		$type = 'review';
		$block = true;
		$block_24 = true;
		return self::send('message/send', compact('target_id', 'clients', 'message', 'company', 'type', 'block', 'offset', 'block_24'));
	}

	static public function message($target_id, $clients, $message, $company, $offset, $attachment = '')
	{
		$type = 'message';
		$block = true;
		$block_24 = true;
		return self::send('message/send', compact('target_id', 'clients', 'message', 'company', 'type', 'block', 'offset', 'attachment', 'block_24'));
	}

	static public function dialog($target_id, $clients, $message, $company, $offset, $attachment = '')
	{
		$type = 'dialog';
		$block = false;
		$block_24 = false;
		return self::send('message/send', compact('target_id', 'clients', 'message', 'company', 'type', 'block', 'offset', 'attachment', 'block_24'));
	}

	static public function appointment($target_id, $clients, $message, $company, $offset, $attachment = '')
	{
		$type = 'appointment';
		$block = true;
		$block_24 = true;
		return self::send('message/send', compact('target_id', 'clients', 'message', 'company', 'type', 'block', 'offset', 'attachment', 'block_24'));
	}

	static public function followUp($target_id, $clients, $message, $company, $offset)
	{
		$type = 'dialog';
		$block = false;
		$block_24 = false;
		return self::send('message/send', compact('target_id', 'clients', 'message', 'company', 'type', 'block', 'offset', 'block_24'));
	}

	static public function alert($target_id, $clients, $message, $company, $offset)
	{
		$type = 'alert';
		$block = false;
		$block_24 = false;
		return self::send('message/send', compact('target_id', 'clients', 'message', 'company', 'type', 'block', 'offset', 'block_24'));
	}

	static public function sendFake($uri, $data, $method = 'POST')
    {
        $client = new Guzzle(['base_uri' => config('app.url')]);
        $response = $client->request($method, $uri, [
			'http_errors' => false,
			'json' => $data,
		]);

        return self::response($response);
	}

	static public function reports($type, $phone, $date, $ids)
	{
		return self::send('reports/get', compact('type', 'phone', 'date', 'ids'));
	}
}