<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default api
    |--------------------------------------------------------------------------
    |
    | Supported: 'google', 'bitly', 'rebrandly'
    |
    */
    'driver' => env('SHORTLINK_DRIVER', 'google'),

    /*
    |--------------------------------------------------------------------------
    | Google api
    |--------------------------------------------------------------------------
    |
    | This is Google shortener api url and key using for aplication.
    |
    */
    'google' => [
        'url' => env('SHORTLINK_GOOGLE_URL', 'https://www.googleapis.com/urlshortener/v1'),
        'key' => env('SHORTLINK_GOOGLE_KEY', 'your_google_api_key'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Bitly api
    |--------------------------------------------------------------------------
    |
    | This is Bitly shortener api url and key using for aplication.
    |
    */
    'bitly' => [
        'url' => env('SHORTLINK_BITLY_URL', 'https://api-ssl.bitly.com/v3'),
        //'key' => env('SHORTLINK_BITLY_KEY', 'e6ac480f8467dc1a73741dd006b6b04c450a9f06'),
        'key' => env('SHORTLINK_BITLY_KEY', 'f5d9819e814e1036603d2d5c7503489118a7d1f9'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rebrandly api
    |--------------------------------------------------------------------------
    |
    | This is Rebrandly shortener api url and key using for aplication.
    |
    */
    'rebrandly' => [
        'url' => env('SHORTLINK_REBRANDLY_URL', 'https://api.rebrandly.com/v1'),
        'key' => env('SHORTLINK_REBRANDLY_KEY', 'your_rebrandly_api_key'),
    ],


];