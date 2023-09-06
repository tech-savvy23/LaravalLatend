<?php

use App\Models\Booking;
use App\Models\Service;
use Faker\Generator as Faker;
use App\Models\BookingService;

$factory->define(BookingService::class, function (Faker $faker) {
    return [
        'booking_id' => function () {
            return factory(Booking::class)->create()->id;
        },
        'service_id' => function () {
            return factory(Service::class)->create()->id;
        },
    ];
});
