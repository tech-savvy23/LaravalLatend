<?php

use App\Models\SpaceType;
use Faker\Generator as Faker;

$factory->define(SpaceType::class, function (Faker $faker) {
    return [
        'space_id' => $faker->numberBetween(1, 100),
        'name' => $faker->unique()->bothify('type ###'),
        'thumbnail' => $faker->imageUrl($width = 200, $height = 200),
        'value' => function () use ($faker) {
            return $faker->numberBetween(0.34, 1);
        }
    ];
});
