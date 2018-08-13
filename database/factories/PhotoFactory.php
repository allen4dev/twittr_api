<?php

use Faker\Generator as Faker;

$factory->define(App\Photo::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return create(App\User::class)->id;
        },
        'path' => $faker->imageUrl,
    ];
});
