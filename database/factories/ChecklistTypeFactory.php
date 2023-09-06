<?php

use App\Models\Checklist;
use Illuminate\Support\Str;
use App\Models\ChecklistType;
use Faker\Generator as Faker;

$factory->define(ChecklistType::class, function (Faker $faker) {
    $title     = $faker->word;
    return [
        'title'        => $title,
        'slug'         => Str::slug($title),
        'checklist_id' => factory(Checklist::class)->create()->id,
    ];
});
