<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Service;
use Faker\Generator as Faker;

$factory->define(Service::class, function (Faker $faker) {
    $name      = $faker->unique()->randomElement(['Electricity', 'Fire', 'Electricity and Fire']);
    $thumbnail = strtolower(str_replace(' ', '-', $name));
    return [
        'name'                => $name,
        'thumbnail'           => "/images/icon/services/{$thumbnail}.png",
        'description'         => $faker->paragraph(),
    ];
});
