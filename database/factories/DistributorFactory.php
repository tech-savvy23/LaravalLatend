<?php

use App\Models\Distributor;
use Faker\Generator as Faker;

$factory->define(Distributor::class, function (Faker $faker) {
    return [
        'name'     => $faker->word,
        'email'    => $faker->word,
        'mobile'   => $faker->numberBetween(1, 100),
        'password' => $faker->word,
    ];
});
