<?php

namespace App\Http\Services;

use App\Homeadvisor;
use App\Text;
use DivArt\ShortLink\Facades\ShortLink;

class HomeAdvisorService
{
    static public function createText($user, $client, $ha, $dialog)
    {
    	$text = $ha->text;
    	$linkPos = strpos($text, 'bit.ly/');
    	if ($linkPos !== false) {
    		$originLink = substr($text, $linkPos, 14);
    		$fakeLink = ShortLink::bitly(config('app.url').'/magic/'.$dialog->id.'/'.$originLink, false);
    		$text = str_replace($originLink, $fakeLink, $text);
		}
		
		if (strpos($text, '[$JobPics]') !== false) {
			$hapage = ShortLink::bitly(config('app.url').'/ha-job/'.$user->id.'/'.$client->id, false);
    		$text = str_replace('[$JobPics]', $hapage, $text);
		}

    	return $text;
	}
}
