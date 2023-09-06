<?php

use App\User;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\Feedback;
use Faker\Generator as Faker;

$factory->define(Feedback::class, function (Faker $faker) {
    return [
        'booking_id' => function () {
            return factory(Booking::class)->create()->id;
        },
        'user_id'    => function () {
            return factory(User::class)->create()->id;
        },
        'partner_id' => function () {
            return factory(Partner::class)->create()->id;
        },
        'body'       => $faker->paragraph,
        'rating'     => $faker->numberBetween(1, 5),
    ];
});
