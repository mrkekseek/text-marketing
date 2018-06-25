<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN', 'mg.medicalreputation.com'),
        'secret' => env('MAILGUN_SECRET', 'key-96eb8c6657297659bc818d15a579c251'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        /* 'key' => 'pk_test_KM8cPI1fQDUJf2Z8R971mJK0',
        'secret' => 'sk_test_rI8xLHVCXOZfY4JnY3ST8k4g',
        'product' => 'prod_Ci23QxLfwi7R7w', */
        'key' => 'pk_live_qfYiDhjIK1fw6XPECmbLafr2',
        'secret' => 'sk_live_fCBDZ0ZmJthRhU8GyOUaJ8lD',
        'product' => 'prod_CjzqhdXnFrPram',
    ],

    'api' => [
        'domain' => env('API_DOMAIN', 'http://54.202.144.178/api/v1/'),
    ],
];
