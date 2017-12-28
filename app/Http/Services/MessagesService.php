<?php

namespace App\Http\Services;

use App\Message;
use App\Text;

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

    static public function createText(Message $message, $clients)
    {
        $text = $message->texts()->create([
            'phones' => count($clients),
            'message' => '',
            'send_at' => $message->date->subHours($message->user->offset),
        ]);

        return $text;
    }

    static public function createReceiver(Text $text, $client)
    {
        $text->receivers()->create([
            'client_id' => $client->id,
            'message' => '',
        ]);
    }
}
