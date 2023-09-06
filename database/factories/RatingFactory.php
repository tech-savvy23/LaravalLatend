<?php

use App\Models\Rating;
use Faker\Generator as Faker;

$factory->define(Rating::class, function (Faker $faker) {
    return [
        'booking_id' => $faker->numberBetween(1, 100),
        'rating'     => $faker->numberBetween(1, 100),
    ];
});
