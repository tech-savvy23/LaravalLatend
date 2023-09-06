<?php

use Faker\Generator as Faker;
use App\Models\BookingAllottee;

$factory->define(BookingAllottee::class, function (Faker $faker) {
    return [
        'booking_id'    => $faker->numberBetween(1, 100),
        'allottee_id'   => $faker->numberBetween(1, 100),
        'allottee_type' => 'type',
    ];
});
