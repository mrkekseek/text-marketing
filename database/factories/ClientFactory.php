<?php

use Faker\Generator as Faker;

$factory->define(\App\Client::class, function (Faker $faker) {
    return [
        'team_id' => 0,
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'phone' => '2222222222',
        'email' => $faker->email,
        'source' => 'HomeAdvisor',
    ];
});
