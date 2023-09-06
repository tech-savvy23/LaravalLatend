<?php

use App\Models\Coupon;
use App\Models\Partner;
use App\Models\PartnerPrice;
use Faker\Generator as Faker;

$factory->define(App\Models\Payment::class, function (Faker $faker) {
    return [
        'booking_id'      => $faker->numberBetween(1, 100),
        'transaction_id'  => $faker->word,
        'service'         => $faker->word,
        'coupon_id'       => factory(Coupon::class)->create()->id,
        'amount'          => 5000,
        'mode'            => 'COD',
        'partnerprice_id' => function () {
            return factory(PartnerPrice::class)->create()->id;
        },
        'partner_type'    => Partner::TYPE_AUDITOR,
        'partner_price'    => $faker->numberBetween(1, 100),
    ];
});
