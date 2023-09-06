<?php

use App\Models\SubService;
use Faker\Generator as Faker;

$factory->define(SubService::class, function (Faker $faker) {
    return [
        'service_id' => $faker->numberBetween(1, 100),
        'name'       => $faker->word,
        'thumbnail'  => $faker->imageUrl($width = 200, $height = 200),
        'body'       => $faker->paragraph,
    ];
});
