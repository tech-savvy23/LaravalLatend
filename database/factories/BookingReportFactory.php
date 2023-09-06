<?php

use App\Models\Report;
use App\Models\Booking;
use App\Models\Checklist;
use App\Models\ReportOption;
use App\Models\ChecklistType;
use Faker\Generator as Faker;
use App\Models\ReportOptionMessage;
use App\Models\BookingMultipleChecklist;

$factory->define(App\Models\BookingReport::class, function (Faker $faker) {
    $checklist   = factory(Checklist::class)->create();
    $type      = factory(ChecklistType::class)->create(['checklist_id' => $checklist->id]);
    $report    = factory(Report::class)->create(['checklist_id' => $checklist->id, 'checklist_type_id' => $type->id]);
    $option    = factory(ReportOption::class, 5)->create(['report_id' => $report->id]);
    $multiple_checklist = factory(BookingMultipleChecklist::class)->create();
    factory(ReportOptionMessage::class)->create(['report_option_id' => $option[0]->id, 'report_id' =>$report->id]);
    return [
        'booking_id'         => function () {
            return factory(Booking::class)->create()->id;
        },
        'checklist_id'       => $checklist->id,
        'checklist_type_id'  => $type->id,
        'report_id'          => $report->id,
        'selected_option_id' => $option[0]->id,
        'observation'        => $faker->paragraph,
        'result'             => $faker->word,
        'multi_checklist_id' => $multiple_checklist->id,
    ];
});
