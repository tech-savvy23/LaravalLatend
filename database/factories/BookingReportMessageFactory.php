<?php

use App\Models\Booking;
use App\Models\BookingReport;
use Faker\Generator as Faker;
use App\Models\ReportOptionMessage;
use App\Models\BookingReportMessage;

$factory->define(BookingReportMessage::class, function (Faker $faker) {
    return [
        'booking_id' => function () {
            return factory(Booking::class)->create()->id;
        },
        'booking_report_id'  => function () {
            return factory(BookingReport::class)->create()->id;
        },
        'report_option_message_id' => function () {
            return factory(ReportOptionMessage::class)->create()->id;
        },
    ];
});
