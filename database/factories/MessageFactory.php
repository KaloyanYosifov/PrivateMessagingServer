<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

$factory->define(\App\Messaging\Models\Message::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'conversation_id' => factory(\App\Messaging\Models\Conversation::class),
        'text' => $faker->sentence,
    ];
});
