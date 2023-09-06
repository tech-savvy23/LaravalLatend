<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Address::class, function (Faker $faker) {
    return [
        'pin'       => $faker->word,
        'body'      => $faker->word,
        'city'      => $faker->city,
        'state'     => $faker->state,
        'landmark'  => $faker->word,
        'latitude'  => '23.123',
        'longitude' => '23.123',
        'user_id'   => $faker->numberBetween(1, 100),
        'house_no' => $faker->word,
    ];
});
