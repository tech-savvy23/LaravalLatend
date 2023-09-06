<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(\App\BookingColorCode::class, function (Faker $faker) {
    return [
        'booking_report_id' => 1,
        'color_code_id' => factory(\App\ColorCode::class)->create()->id,
    ];
});
