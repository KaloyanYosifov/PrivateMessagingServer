<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Messaging\Models\Message::class, function (Faker $faker) {
    return [
        'from_user_id' => factory(\App\User::class),
        'to_user_id' => factory(\App\User::class),
        'text' => $faker->sentence,
    ];
});
