<?php

use Faker\Generator as Faker;

$factory->define(App\Activity::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return create(App\User::class)->id;
        },
        'subject_id' => function () {
            return create(App\Tweet::class)->id;
        },
        'subject_type' => 'App\Tweet',
        'action' => 'created_tweet'
    ];
});
