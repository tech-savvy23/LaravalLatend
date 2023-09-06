<?php

use App\Models\RescheduleReason;
use Illuminate\Support\Str;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(RescheduleReason::class, function (Faker\Generator $faker) {
    return [
        'reason'           => $faker->sentence,
    ];
});
