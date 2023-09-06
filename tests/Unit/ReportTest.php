<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Media;
use App\Models\Report;
use App\Models\Checklist;
use App\Models\ReportOption;
use App\Models\BookingReport;
use App\Models\ChecklistType;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReportTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_checklist()
    {
        $checklist = factory(Checklist::class)->create();
        $Report    = factory(Report::class)->create(['checklist_id' => $checklist->id]);
        $this->assertInstanceOf(Checklist::class, $Report->checklist);
    }

    /** @test */
    public function it_belongs_to_booking_report()
    {
        $checklist     = factory(Checklist::class)->create();
        $Report        = factory(Report::class)->create(['checklist_id' => $checklist->id]);
        $bookingReport = factory(BookingReport::class)->create(['report_id'=>$Report->id]);
        $this->assertInstanceOf(BookingReport::class, $Report->bookingReport[0]);
    }

    /** @test */
    public function it_belongs_to_checklist_type()
    {
        $checklist_type = factory(ChecklistType::class)->create();
        $Report         = factory(Report::class)->create(['checklist_type_id' => $checklist_type->id]);
        $this->assertInstanceOf(ChecklistType::class, $Report->checklist_type);
    }

    /** @test */
    public function it_has_many_options()
    {
        $Report  = factory(Report::class)->create();
        $options = factory(ReportOption::class)->create(['Report_id' => $Report->id]);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $Report->options);
    }

    /** @test */
    public function it_has_many_media()
    {
        $report  = $this->create_report();
        $media   = factory(Media::class)->create(['model_id'=>$report->id, 'model_type'=>get_class($report)]);
        $this->assertInstanceOf(Media::class, $report->images[0]);
    }
}
