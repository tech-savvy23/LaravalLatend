<?php

use App\Models\Coupon;
use Faker\Generator as Faker;

$factory->define(Coupon::class, function (Faker $faker) {
    return [
        'name'     => $faker->unique()->bothify('Coupon #'),
        'discount' => $faker->numberBetween(1, 100),
        'service'  => $faker->word,
    ];
});
