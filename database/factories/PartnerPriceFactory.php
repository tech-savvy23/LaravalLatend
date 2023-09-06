<?php

use App\Models\PartnerPrice;
use Faker\Generator as Faker;

$factory->define(PartnerPrice::class, function (Faker $faker) {
    return [
        'state_id' => $faker->numberBetween(1, 100),
        'city_id'  => $faker->numberBetween(1, 100),
        'type'     => $faker->word,
        'price'    => 100.00,
    ];
});
