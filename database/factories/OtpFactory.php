<?php

use App\User;
use App\Models\Common\Otp;
use Faker\Generator as Faker;

$factory->define(Otp::class, function (Faker $faker) {
    $user      = factory(User::class)->create();
    return [
        'model_id'   => $user->id,
        'model_type' => get_class($user),
        'otp'        => $faker->randomNumber(6),
        'for_id'     => $user->id,
        'for_type'   => get_class($user),
    ];
});
