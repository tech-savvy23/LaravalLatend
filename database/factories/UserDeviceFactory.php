<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\User\UserDevice::class, function (Faker $faker) {
    return [
        'user_id'     => $faker->numberBetween(1,10),
        'device_id'   => \Illuminate\Support\Str::random(10),
        'device_type' => 'android',
        'token'       => \Illuminate\Support\Str::random(200),
    ];
});
