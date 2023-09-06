<?php

use App\Models\Report;
use App\Models\ReportOption;
use Faker\Generator as Faker;

$factory->define(ReportOption::class, function (Faker $faker) {
    return [
        'title'     => $faker->word,
        'report_id' => factory(Report::class)->create()->id,
    ];
});
