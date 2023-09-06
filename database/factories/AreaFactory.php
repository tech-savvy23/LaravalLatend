<?php

use App\Models\Area;
use Faker\Generator as Faker;

$factory->define(Area::class, function (Faker $faker) {
    return [
        'type'   => $faker->randomElement(['bhk', 'meter', 'sq. meter']),
        'status' => $faker->boolean(),
        'amount' => $faker->numberBetween(3000, 4000),
    ];
});
