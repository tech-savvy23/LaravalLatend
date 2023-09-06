<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Report;
use App\Models\ReportOption;
use App\Models\ReportOptionMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReportOptionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_report()
    {
        $report        = factory(Report::class)->create();
        $ReportOption  = factory(ReportOption::class)->create(['report_id' => $report->id]);
        $this->assertInstanceOf(Report::class, $ReportOption->report);
    }

    /** @test */
    public function it_has_many_messages()
    {
        $report        = factory(Report::class)->create();
        $ReportOption  = factory(ReportOption::class)->create(['report_id' => $report->id]);
        $msg           = $this->create_report_message([
            'report_id'=> $report->id, 'report_option_id' => $ReportOption->id,
        ]);
        $this->assertInstanceOf(ReportOptionMessage::class, $ReportOption->messages[0]);
    }
}
