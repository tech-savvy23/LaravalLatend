<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name'        => $faker->name,
        'last_name'         => 'last_name',
        'email'             => $faker->unique()->safeEmail,
        'mobile'            => $faker->numberBetween(1000000000, 9999999999),
        'email_verified_at' => null,
        'mobile_verified'   => true,
        'active'            => true,
        'password'          => 'secret123',
        'remember_token'    => Str::random(10),
    ];
});
