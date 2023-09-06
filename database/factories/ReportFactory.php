<?php

use App\Models\Report;
use Faker\Generator as Faker;

$factory->define(Report::class, function (Faker $faker) {
    return [
        'title'             => $faker->paragraph,
        'checklist_id'      => $faker->numberBetween(1, 100),
        'checklist_type_id' => $faker->numberBetween(1, 100),
    ];
});
