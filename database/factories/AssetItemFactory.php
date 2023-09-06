<?php

use App\Models\AssetItem;
use Faker\Generator as Faker;

$factory->define(AssetItem::class, function (Faker $faker) {
    return [
        'area_id'     => $faker->numberBetween(1, 100),
        'description' => $faker->paragraph,
    ];
});
