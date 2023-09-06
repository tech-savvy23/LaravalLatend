<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\BookingReport;
use App\Models\BookingAllottee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingMultipleChecklistTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_multiple_checklist_type_for_booking()
    {
        $booking_checklist = $this->createMultipleChecklist();
        $this->assertDatabaseHas('booking_multiple_checklists', $booking_checklist->toArray());
    }

    /** @test */
    public function api_can_select_the_multiple_checklist_type_for_booking()
    {
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($partner, 'partner');

        $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);

        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);

        $checklist  = $this->create_checklist();
        $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);

        $data = [
            'booking_id'   => $booking->id,
            'checklist_id' => $checklist->id,
            'title'        => 'Multiple 1',
        ];

        $res = $this->postJson(route('lead-multiple-checklist'), $data);
        $res->assertStatus(201)->assertJson([
            'data' => $data,
        ]);
    }

    /** @test */
    // public function api_can_not_enter_the_duplicate_multiple_checklist_type_for_booking()
    // {
    //     $this->withoutExceptionHandling();

    //     $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
    //     $this->actingAs($partner, 'partner');

    //     $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);

    //     $this->create_booking_service(['booking_id' => $booking->id]);
    //     $this->create_booking_space(['booking_id' => $booking->id]);

    //     factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);

    //     $checklist  = $this->create_checklist();
    //     $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);

    //     $this->createMultipleChecklist([
    //         'booking_id' => $booking->id,
    //         'checklist_type_id' => $type->id,
    //         'title' => 'Multiple 1'
    //         ]);

    //     $data = [
    //         'booking_id' => $booking->id,
    //         'checklist_type_id' => $type->id,
    //         'title' => 'Multiple 1'
    //     ];

    //     $res = $this->postJson(route('lead-multiple-checklist-type'),$data);

    //     $res->assertStatus(422)->assertJsonStructure([
    //         'errors' => [
    //             'error'
    //         ]
    //     ]);

    // }

    /** @test */
    public function api_can_delete_a_multiple_checklist_type_for_booking()
    {
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($partner, 'partner');

        $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);

        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);

        $checklist  = $this->create_checklist();
        $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);

        $booking_multiple_checklist = $this->createMultipleChecklist([
            'booking_id'   => $booking->id,
            'checklist_id' => $checklist->id,
            'title'        => 'Multiple 1',
        ]);

        $res = $this->deleteJson(route('delete-multiple-checklist', [$booking_multiple_checklist->id]));
        $res->assertStatus(204);

        $this->assertDatabaseMissing('booking_multiple_checklists', $booking_multiple_checklist->toArray());
    }

    /** @test */
    public function api_can_delete_a_multiple_checklist_type_for_booking_and_also_delete_all_reports()
    {
        Storage::fake();
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($partner, 'partner');

        $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);

        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);

        $checklist  = $this->create_checklist();
        $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);

        $booking_multiple_checklist = $this->createMultipleChecklist([
            'booking_id'   => $booking->id,
            'checklist_id' => $checklist->id,
            'title'        => 'Multiple 1',
        ]);

        $image            = \Illuminate\Http\Testing\File::image('image.jpg');
        $image            = base64_encode(file_get_contents($image));

        //Booking Report first
        $bookingReport    = $this->create_bookingreport(['multi_checklist_id' => $booking_multiple_checklist->id, 'booking_id' => $booking->id]);

        $image1           = $this->postJson(route('booking.report.imageupload', $bookingReport->id), ['image' => $image])->assertStatus(201)->json();
        $disk             = env('DISK', 'public');
        Storage::disk($disk)->assertExists("images/{$image1['name']}");

        $image2           = $this->postJson(route('booking.report.imageupload', $bookingReport->id), ['image' => $image])->assertStatus(201)->json();
        Storage::disk($disk)->assertExists("images/{$image2['name']}");

        $this->assertDatabaseHas('booking_reports', [
            'id' => $bookingReport->id,
        ]);

        $this->assertDatabaseHas('media', [
            'model_id'   => $bookingReport->id,
            'model_type' => get_class(new BookingReport()),
        ]);

        $response = $this->deleteJson(route('delete-multiple-checklist', [$booking_multiple_checklist->id]));
        $response->assertStatus(204);

        $this->assertDatabaseMissing('booking_multiple_checklists', $booking_multiple_checklist->toArray());

        $this->assertDatabaseMissing('booking_reports', [
            'multi_checklist_id' => $booking_multiple_checklist->id,
        ]);

        $this->assertDatabaseMissing('media', [
            'model_id'   => $bookingReport->id,
            'model_type' => get_class(new BookingReport()),
        ]);
        Storage::assertMissing("images/{$image1['name']}");
        Storage::assertMissing("images/{$image2['name']}");
    }

    /** @test */
    public function api_can_update_a_multiple_checklist_type_for_booking()
    {
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($partner, 'partner');

        $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);

        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);

        $checklist  = $this->create_checklist();
        $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);

        $booking_multiple_checklist = $this->createMultipleChecklist([
            'booking_id'   => $booking->id,
            'checklist_id' => $checklist->id,
            'title'        => 'Multiple 1',
        ]);

        $data = ['title' => 'Multiple 2'];

        $res = $this->patchJson(route('update-multiple-checklist', [$booking_multiple_checklist->id]), $data);
        $res->assertStatus(200)->assertJson([
            'data' => [
                'booking_id'   => $booking->id,
                'checklist_id' => $checklist->id,
                'title'        => 'Multiple 2',
            ],
        ]);
    }

    /** @test */
    public function get_booking_multiple_checklist_type()
    {
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($partner, 'partner');

        $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED]);

        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);

        $checklist  = $this->create_checklist();
        $type       = $this->create_checklisttype(['checklist_id' => $checklist->id]);

        $this->createMultipleChecklist([
            'booking_id'   => $booking->id,
            'checklist_id' => $checklist->id,
            'title'        => 'Multiple 1',
        ]);
        $res = $this->getJson(route('get-multiple-checklist', [$booking->id, $checklist->id]));
        $res->assertStatus(200);
    }
}
