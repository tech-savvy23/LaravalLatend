<?php

use App\Models\Space;
use App\Models\Booking;
use App\Models\BookingSpace;
use Faker\Generator as Faker;

$factory->define(BookingSpace::class, function (Faker $faker) {
    return [
        'booking_id'    => function () {
            return factory(Booking::class)->create()->id;
        },
        'space_id'      => function () {
            return factory(Space::class)->create()->id;
        },
        'space_type_id' => $faker->boolean(),
    ];
});
