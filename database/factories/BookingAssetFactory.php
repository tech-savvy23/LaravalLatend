<?php

use App\Models\BookingAsset;
use Faker\Generator as Faker;

$factory->define(BookingAsset::class, function (Faker $faker) {
    return [
        'asset_item_id' => $faker->numberBetween(1, 100),
        'booking_id'    => $faker->numberBetween(1, 100),
        'number'        => $faker->numberBetween(1, 100),
        'phase'         => $faker->word,
        'voltage'       => $faker->numberBetween(1, 100),
        'current'       => $faker->numberBetween(1, 100),
    ];
});
