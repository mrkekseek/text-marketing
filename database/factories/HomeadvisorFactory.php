<?php

use Faker\Generator as Faker;

$factory->define(\App\Homeadvisor::class, function (Faker $faker) {
    return [
        'users_id' => 0,
        'text' => 'Test text',
        'send_request' => true,
        'active' => true,
    ];
});
