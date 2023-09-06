<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Service;
use Faker\Generator as Faker;

$factory->define(\App\Models\BookingFile::class, function (Faker $faker) {

    return [
        'booking_id'     => $faker->numberBetween(1, 100),
        'pdf'           => $faker->imageUrl(),
    ];
});
