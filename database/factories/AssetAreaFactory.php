<?php

use App\Models\AssetArea;
use Faker\Generator as Faker;

$factory->define(AssetArea::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
    ];
});
