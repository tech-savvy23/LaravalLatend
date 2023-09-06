<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Report;
use App\Models\ReportOption;
use Faker\Generator as Faker;
use App\Models\ReportOptionMessage;

$factory->define(ReportOptionMessage::class, function (Faker $faker) {
    $report    = factory(Report::class)->create();
    return [
        'report_id'        => $report->id,
        'report_option_id' => factory(ReportOption::class)->create(['report_id' => $report->id])->id,
        'message'          => $faker->paragraph,
    ];
});
