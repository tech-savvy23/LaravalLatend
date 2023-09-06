<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Common\ApiKey;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(ApiKey::class, function (Faker $faker) {
    return [
        'key' => Str::random(20),
        'device' => $faker->unique()->randomElement(['android','ios','web'])
    ];
});
