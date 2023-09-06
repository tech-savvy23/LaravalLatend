<?php

use App\Models\BookingCancel;
use Faker\Generator as Faker;

$factory->define(BookingCancel::class, function (Faker $faker) {
    return [
        'booking_id' => $faker->numberBetween(1, 100),
        'user_id'    => $faker->numberBetween(1, 100),
        'user_type'  => 'type',
        'reason'     => $faker->paragraph,
    ];
});
