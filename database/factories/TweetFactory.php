<?php

use Faker\Generator as Faker;

$factory->define(App\Tweet::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return create(App\User::class)->id;
        },
        'body' => $faker->text,
    ];
});
