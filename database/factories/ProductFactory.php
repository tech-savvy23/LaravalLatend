<?php

use App\Models\City;
use App\Models\State;
use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'description' => $faker->word,
        'uom'         => '1',
        'price'       => 100,
        'maker'       => 'Anchor',
        'state_id'    => function () {
            return factory(State::class)->create()->id;
        },
        'city_id' => function () {
            return factory(City::class)->create()->id;
        },
    ];
});
