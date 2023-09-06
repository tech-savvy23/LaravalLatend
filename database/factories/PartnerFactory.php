<?php

use App\Models\Partner;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Partner::class, function (Faker $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'type'           => Partner::TYPE_AUDITOR,
        'password'       => 'secret123',
        'longitude'      => $faker->longitude,
        'latitude'       => $faker->latitude,
        'phone'          => $faker->unique()->randomNumber(9),
        'city'           => $faker->word,
        'state'          => $faker->word,
        'pin'            => $faker->numberBetween(100000, 999999),
        'remember_token' => Str::random(10),
        'active'         => true,
        'police_verified_at' => null
    ];
});
