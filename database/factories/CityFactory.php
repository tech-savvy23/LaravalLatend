<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\City;
use App\Models\State;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {
    return [
        'state_id' => function () {
            return factory(State::class)->create()->id;
        },
        'name' => $faker->city,
    ];
});
