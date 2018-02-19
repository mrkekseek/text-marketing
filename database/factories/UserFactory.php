<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\User::class, function (Faker $faker) {
    static $password;

    return [
        'plans_id' => 'home-advisor-contractortexter',
        'firstname' => $faker->name,
        'lastname' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'active' => true,
        'type' => 2,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'teams_id' => function() {
            return factory(App\Team::class)->create()->id;
        }
    ];
});
