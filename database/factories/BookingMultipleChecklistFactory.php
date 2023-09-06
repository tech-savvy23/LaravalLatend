<?php

use App\Models\Booking;
use App\Models\Checklist;
use Faker\Generator as Faker;

$factory->define(App\Models\BookingMultipleChecklist::class, function (Faker $faker) {
    return [
        'booking_id' => function(){
            return factory(Booking::class)->create()->id;
        },
        'checklist_id' => function(){
            return factory(Checklist::class)->create()->id;
        },
        'title'   => $faker->title,
    ];
});
