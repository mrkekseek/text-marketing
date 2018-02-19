<?php

use Faker\Generator as Faker;

$factory->define(\App\Link::class, function (Faker $faker) {
    return [
        'users_id' => 0,
        'code' => '123456',
        'url' => function (array $link) {
            return config('app.url').'/home-advisor/'.urlencode($link['code']);
        },
        'success' => function(array $link) {
            return 'User '.$link['code'];
        },
    ];
});
