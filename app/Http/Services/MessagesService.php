<?php

namespace App\Http\Services;

class MessagesService
{
    static public function receivers($text, $clients)
	{
        foreach ($text->receivers as $receiver) {
            if ( ! empty($clients[$receiver->client->phone])) {
                $client = $clients[$receiver->client->phone];
                $receiver->update([
                    'finish' => $client['finish'],
                    'success' => $client['success'],
                    'message' => ! empty($client['message']) ? $client['message'] : '',
                ]);
            }
        }
    }
}
