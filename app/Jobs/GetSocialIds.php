<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DiDom\Document;

class GetSocialIds implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $googleApiKey = 'AIzaSyBN_OUWOCTBwlgf_gBz6DTL1_jnT2JqxWY';
    public $facebookToken = '1797563467234797|ycsQAJVBO5fNRTqjI3EsQ63OIqo';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (strpos($this->url->url, 'facebook') !== false) {
            $this->getFacebookId($this->url);
        }

        if (strpos($this->url->url, 'google') !== false) {
            $this->getGoogleId($this->url);
        }

        if (strpos($this->url->url, 'yelp') !== false) {
            $this->getYelpId($this->url);
        }
    }
    
    public function getFacebookId($url)
    {
        $part = explode('/', $url->url);
        $part = array_pop($part);

        if ( ! empty($part)) {
            $facebook = @file_get_contents('https://graph.facebook.com/'.$part.'?access_token='.$this->facebookToken);
            $result = json_decode($facebook, true);

            if (empty($result)) {
                $temp = explode('-', $part);
                $part = array_pop($temp);
                $facebook = @file_get_contents('https://graph.facebook.com/'.$part.'?access_token='.$this->facebookToken);
                $result = json_decode($facebook, true);
            }
            
            if ( ! empty($result['id'])) {
                $url->update(['social_id' => $result['id']]);
            }
        }
    }

    public function getGoogleId($url)
    {
        $query = $url->url;
        $googleapis = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=';
        $location = '';
        $id = '';

        $temp = explode('place/', $query);
        if ( ! empty($temp[1])) {
            $temp = explode('/', $temp[1]);
            if ( ! empty($temp)) {
                $query = $temp[0];
                $location = explode(',', $temp[1]);
                $location = str_replace('@', '', $location[0].','.$location[1]);
            }
        }

        $json = array_shift(json_decode(file_get_contents($googleapis.$query.'&location='.$location.'&radius=10&key='.$this->googleApiKey.'&language=en'))->results);
        if ($json) {
            $id = $json->place_id;
        }

        if ( ! empty($id)) {
            $url->update(['social_id' => $id]);
        }
    }

    public function getYelpId($url)
    {
        $document = new Document($url->url, true);
        $link = $document->find('.ybtn.ybtn--primary.war-button::attr(href)');
        $link = parse_url($link[0]);
        $link = explode('/', $link['path']);
        $id = array_pop($link);
        $url->update(['social_id' => $id]);
    }
}
