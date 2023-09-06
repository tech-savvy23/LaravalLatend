<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_can_give_all_report()
    {
        $checklist = $this->create_checklist();
        $this->create_report(['checklist_id' => $checklist->id]);
        // \DB::connection()->enableQueryLog();

        $this->getJson(route('report.index', $checklist->slug))->assertOk()->assertJsonStructure(['data']);
        // dd(\DB::getQueryLog());
    }

    /** @test */
    public function api_can_give_all_report_of_a_checklist_type()
    {
        $checklist  = $this->create_checklist();
        $type1      = $this->create_checklisttype(['checklist_id' => $checklist->id]);
        $type2      = $this->create_checklisttype(['checklist_id' => $checklist->id]);
        $this->create_report(['checklist_id' => $checklist->id, 'checklist_type_id' => $type1->id], 2);
        $this->create_report(['checklist_id' => $checklist->id, 'checklist_type_id' => $type2->id], 2);

        $res = $this->getJson(route('report.bytype', ['type' => $type1->slug, 'checklist' => $checklist->slug]))->assertOk()->assertJsonStructure(['data'])->json();

        $this->assertEquals(2, count($res['data']));
    }

    /** @test */
    public function api_can_give_all_report_with_logged_in_auditor_selected_option()
    {
        $booking            = $this->create_booking([], 2);
        $checklist          = $this->create_checklist();
        $type               = $this->create_checklisttype(['checklist_id' => $checklist->id]);
        $report             = $this->create_report(['checklist_id' => $checklist->id, 'checklist_type_id' => $type->id], 2);
        $options            = $this->create_reportoption(['report_id' => $report[0]->id], 4);
        $multiple_checklist = $this->createMultipleChecklist();
        $this->create_bookingreport([
            'booking_id'         => $booking[0]->id,
            'checklist_id'       => $checklist->id,
            'checklist_type_id'  => $type->id,
            'report_id'          => $report[0]->id,
            'selected_option_id' => $options[0]->id,
            'multi_checklist_id' => $multiple_checklist->id,
        ]);
        // \DB::connection()->enableQueryLog();
        $res        = $this->getJson(
            route('report.bytype', [
                'type'      => $type->slug,
                'checklist' => $checklist->slug,
            ]),
            ['booking_id'=> $booking[0]->id, 'multi_id'     => $multiple_checklist->id, ]
        )->assertOk()->json();
        // dd(\DB::getQueryLog());

        $this->assertEquals(2, count($res['data']));
        // $this->assertEquals(1, $res['data'][0]['selected_option_id']);
    }

    /** @test */
    public function api_can_give_single_report()
    {
        $checklist = $this->create_checklist();
        $report    = $this->create_report();
        $this->getJson(route('report.show', ['checklist'=>$checklist->slug, 'report'=>$report->id]))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_report()
    {
        $checklist         = $this->create_checklist();
        $res               = $this->postJson(route('report.store', $checklist->slug), [
            'title'=> 'Laravel', 'options'=> [
                ['title' => 'yes', 'messages'=>[['value'=>'hello1'], ['value'=> 'hello11']]],
                ['title' => 'no', 'messages' => [['value'=>'hello2']]],
                ['title' => 'other'],
            ],
        ])
        ->assertStatus(201);
        // dd($res->json());
        $this->assertDatabaseHas('Reports', ['title'=>'Laravel']);
        $this->assertDatabaseHas('Report_options', ['title'=>'yes']);
        $this->assertDatabaseHas('Report_options', ['title'=>'no']);
        $this->assertDatabaseHas('Report_option_messages', ['message'=>'hello1']);
        $this->assertDatabaseHas('Report_option_messages', ['message'=>'hello11']);
        $this->assertDatabaseHas('Report_option_messages', ['message'=>'hello2']);
    }

    /** @test */
    public function api_can_store_new_report_with_images()
    {
        Storage::fake();
        $image              = \Illuminate\Http\Testing\File::image('image.jpg');
        $image              = base64_encode(file_get_contents($image));
        $multiple_checklist = $this->createMultipleChecklist();
        $bookingReport      = $this->create_bookingreport(['multi_checklist_id' => $multiple_checklist->id]);
        $res                = $this->postJson(route('booking.report.imageupload', $bookingReport->id), [
            'image' => $image,
        ])
        ->assertStatus(201);
        array_map('unlink', glob(storage_path('app/public/*.*')));
    }

    /** @test */
    public function api_can_delete_report_images()
    {
        Storage::fake();
        $image              = \Illuminate\Http\Testing\File::image('image.jpg');
        $image              = base64_encode(file_get_contents($image));
        $multiple_checklist = $this->createMultipleChecklist();
        $bookingReport      = $this->create_bookingreport(['multi_checklist_id' => $multiple_checklist->id]);
        $res                = $this->postJson(route('booking.report.imageupload', $bookingReport->id), ['image' => $image])->assertStatus(201)->json();
        $disk               = env('DISK', 'public');
        Storage::disk($disk)->assertExists("images/{$res['name']}");
        // array_map('unlink', glob(storage_path('app/public/*.*')));
        $this->postJson(route('booking.report.imageDelete', $res['id']))->assertStatus(204);
        $this->assertDatabaseMissing('media', ['id' => $res['id']]);
        Storage::assertMissing("images/{$res['name']}");
    }

    /** @test */
    public function api_can_update_report()
    {
        $checklist         = $this->create_checklist();
        $report            = $this->create_report(['checklist_id' => $checklist->id]);
        $option            = $this->create_reportoption(['report_id' => $report->id]);
        $this->create_report_message(['report_option_id'=>$option->id, 'report_id' => $report->id]);

        $this->putJson(route('report.update', ['checklist'=>$checklist->slug, 'report'=>$report->id]), [
            'title'=> 'updatedValue', 'options'=> [
                ['title' => 'yes', 'messages'=>[['value'=>'hello1'], ['value'=> 'hello11']], 'id' => $option->id],
            ],
        ])
        ->assertStatus(202);
        $this->assertDatabaseHas('Reports', ['title'=>'updatedValue']);
    }

    /** @test */
    public function api_can_delete_report()
    {
        $checklist = $this->create_checklist();
        $report    = $this->create_report(['checklist_id' => $checklist->id]);
        $option    = $this->create_reportoption(['report_id' => $report->id]);
        $this->create_report_message(['report_id' => $report->id, 'report_option_id' => $option->id]);

        $this->assertDatabaseHas('Report_options', ['report_id'=>$report->id]);

        $this->deleteJson(route('report.destroy', ['checklist'=>$checklist->slug, 'report'=>$report->id]))->assertStatus(204);

        $this->assertDatabaseMissing('Reports', ['title'=>$report->title]);
        $this->assertDatabaseMissing('Report_options', ['report_id'=>$report->id]);
        $this->assertDatabaseMissing('Report_option_messages', ['report_id'=>$report->id]);
    }
}
