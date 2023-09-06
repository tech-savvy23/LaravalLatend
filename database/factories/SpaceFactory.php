<?php

use App\Models\Space;
use Faker\Generator as Faker;

$factory->define(Space::class, function (Faker $faker) {
    return [
        'name'      => $faker->unique()->randomElement(['Homespace', 'Workspace']),
        'thumbnail' => $faker->imageUrl($width = 200, $height = 200),
    ];
});
