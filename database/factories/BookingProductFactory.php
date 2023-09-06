<?php

use Faker\Generator as Faker;

$factory->define(App\Models\BookingProduct::class, function (Faker $faker) {
    return [
        'booking_id' => $faker->numberBetween(1, 100),
        'product_id' => $faker->numberBetween(1, 100),
        'quantity'   => $faker->numberBetween(1, 100),
        'price'      => $faker->numberBetween(1, 100),
    ];
});
