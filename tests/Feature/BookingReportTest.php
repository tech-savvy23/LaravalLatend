<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BookingReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookingReportTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_give_all_bookingreport()
    {
        $multiple_checklist =  $this->createMultipleChecklist();
        $this->create_bookingreport(['multi_checklist_id' => $multiple_checklist->id], 10);
        // \DB::connection()->enableQueryLog();
        $res = $this->getJson(route('bookingreport.index'))->assertOk()->assertJsonStructure(['data'])->json();
        // dd(\DB::getQueryLog());
        // dd($res);
    }

    /** @test */
    public function api_can_give_all_report_of_booking()
    {
        $booking = $this->create_booking();
        $this->create_bookingreport(['booking_id' => $booking->id], 10);
        // \DB::connection()->enableQueryLog();
        $res = $this->getJson(route('booking.report', $booking->id))->assertOk()->assertJsonStructure(['data'])->json();
        // dd(\DB::getQueryLog());
        // dd(count($res['data']));
    }

    /** @test */
    public function api_can_give_single_bookingreport()
    {
        $booking            = $this->create_booking();
        $multiple_checklist = $this->createMultipleChecklist();
        $this->create_bookingreport(['booking_id' => $booking->id,  'multi_checklist_id' => $multiple_checklist->id]);
        $this->getJson(route('bookingreport.show', $booking->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_bookingreport()
    {
        $bookingreport = factory(BookingReport::class)->make(['booking_id'=>'Laravel']);
        $this->postJson(route('bookingreport.store'), $bookingreport->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Booking_reports', ['booking_id'=>'Laravel']);
    }

    /** @test */
    public function api_can_store_new_bookingreport_along_with_image()
    {
        $this->withoutExceptionHandling();
        Storage::fake();
        $this->actingAs($this->create_user(), 'partner');
        $image                   = \Illuminate\Http\Testing\File::image('image.jpg');
        $image                   = base64_encode(file_get_contents($image));
        $multiple_checklist      = $this->createMultipleChecklist();
        $bookingreport           = factory(BookingReport::class)->make(['booking_id'=>'Laravel', 'multi_checklist_id' => $multiple_checklist->id]);
        $bookingreport['images'] = ["data:image/png;base64,{$image}", "data:image/png;base64,{$image}"];
        $res                     = $this->postJson(route('bookingreport.store', $bookingreport->toArray()))
                                ->assertSuccessful()->json();

        Storage::disk('public')->assertExists($res['data']['images'][0]['name']);
        Storage::disk('public')->assertExists($res['data']['images'][1]['name']);
        $this->assertDatabaseHas('media', ['model_type' => get_class($bookingreport)]);
    }

    /** @test */
    public function api_can_update_bookingreport()
    {
        $multiple_checklist = $this->createMultipleChecklist();
        $bookingreport      = $this->create_bookingreport(['multi_checklist_id' => $multiple_checklist->id]);
        $this->putJson(route('bookingreport.update', $bookingreport->id), ['booking_id'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Booking_reports', ['booking_id'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_bookingreport()
    {
        $multiple_checklist = $this->createMultipleChecklist();
        $bookingreport      = $this->create_bookingreport(['multi_checklist_id' => $multiple_checklist->id]);
        $this->deleteJson(route('bookingreport.destroy', $bookingreport->id))->assertStatus(204);
        $this->assertDatabaseMissing('Booking_reports', ['booking_id'=>$bookingreport->booking_id]);
    }
}
