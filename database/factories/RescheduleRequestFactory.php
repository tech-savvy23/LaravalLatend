<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\RescheduleRequest::class, function (Faker $faker) {
    return [
        'booking_id'    => $faker->numberBetween(1, 100),
        'allottee_id'   => $faker->numberBetween(1, 100),
        'allottee_type' => 'type',
        "reason" => 'test',
        'date_time' => \Carbon\Carbon::now()->addDay(),
        'status' => 0
    ];
});
