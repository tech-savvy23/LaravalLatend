<?php

use App\Models\Booking;
use App\Models\Checklist;
use App\Models\ChecklistType;
use Faker\Generator as Faker;

$factory->define(App\Models\BookingDevice::class, function (Faker $faker) {
   $value = ['value1' => '123', 'value2' => '123'];
    return [
        'booking_id' => function(){
            return factory(Booking::class)->create()->id;
        },
        'checklist_type_id' => function(){
            return factory(ChecklistType::class)->create()->id;
        },
        'title'   => $faker->title,
        'value' => $value,
        'checklist_id' =>function(){
            return factory(Checklist::class)->create()->id;
        },
        'result' => $faker->name

    ];
});
