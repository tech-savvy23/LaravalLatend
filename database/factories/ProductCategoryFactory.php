<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\ProductCategory;

$factory->define(ProductCategory::class, function (Faker $faker) {
    $title     = $faker->word;
    return [
        'title' => $title,
        'slug'  => Str::slug($title),
    ];
});
