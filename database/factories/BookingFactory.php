<?php

use App\User;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\Address;
use App\Models\Booking;
use Faker\Generator as Faker;

$factory->define(Booking::class, function (Faker $faker) {
    return [
        'address_id'      => function () {
            return factory(Address::class)->create()->id;
        },
        'user_id'         => function () {
            return factory(User::class)->create()->id;
        },
        'status'          => $faker->numberBetween(0, 2),
        'otp_status'      => $faker->boolean(),
        'booking_time'    => Carbon::now(),
        'contractor_time' => Carbon::now(),
        'area_id'         => function () {
            return factory(Area::class)->create()->id;
        },
        'area_number'       => $faker->numberBetween(1, 10),
        'reschedule_status' => 0,
    ];
});
