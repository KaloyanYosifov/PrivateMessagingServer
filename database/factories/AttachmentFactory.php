<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Attachment;
use Faker\Generator as Faker;
use App\Enums\AttachmentType;

$factory->define(Attachment::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(AttachmentType::values()),
        'path' => '/storage/public/' . $faker->title . $faker->fileExtension,
    ];
});
