<?php

use App\Models\Checklist;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Checklist::class, function (Faker $faker) {
    $title     = $faker->unique()->word;
    return [
        'title'    => $title,
        'slug'     => Str::slug($title),
    ];
});
