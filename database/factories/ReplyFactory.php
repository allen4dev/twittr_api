<?php

use Faker\Generator as Faker;

$factory->define(App\Reply::class, function (Faker $faker) {
    $user = create(App\User::class);
    
    return [
        'user_id' => function () use ($user) {
            return $user->id;
        },
        'tweet_id' => function () use ($user) {
            return create(App\Tweet::class, ['user_id' => $user->id ])->id;
        },
        'body' => $faker->sentence,
    ];
});
