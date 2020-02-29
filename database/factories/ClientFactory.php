<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Laravel\Passport\Client;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'secret' => $faker->md5,
        'redirect' => $faker->url,
        'personal_access_client' => false,
        'password_client' => false,
        'revoked' => $faker->boolean,
    ];
});

$factory->state(Client::class, 'personal', function ($faker) {
    return [
        'personal_access_client' => true,
    ];
});

$factory->state(Client::class, 'password', function ($faker) {
    return [
        'password_client' => true,
    ];
});
