<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Partner;
use App\PartnerDocument;
use Faker\Generator as Faker;

$factory->define(PartnerDocument::class, function (Faker $faker) {
    $name   = $faker->unique()->randomElement(['PAN', 'GST', 'BANK']);
    $thumbnail = strtolower(str_replace(' ', '-', $name));
    return [
        'partner_id' =>  function () {
            return factory(Partner::class)->create(['type'=> Partner::TYPE_AUDITOR])->id;
        },
        'pan' => '12345678',
        'gst' => '12345678',
        'bank' => '12345678',
    ];
});
