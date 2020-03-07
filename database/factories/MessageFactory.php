<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

$preGenerateSentences = [
    'Are you a pretty one or what!',
    'Привет Привет',
    'How are you?',
    'Yes i know i am cringe',
    'Vicky стига',
    'Искаше ми се',
    'I am coming with you',
    'Yes, but no',
    'Welcome to the club!',
];

$factory->define(\App\Messaging\Models\Message::class, function (Faker $faker) use ($preGenerateSentences) {
    return [
        'user_id' => factory(User::class),
        'conversation_id' => factory(\App\Messaging\Models\Conversation::class),
        'text' => $faker->randomElement($preGenerateSentences),
    ];
});
