<?php

namespace Tests\Feature;

use App\Models\BookingFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingFileTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function api_upload_booking_file_pdf_is_null()
    {
        $booking = $this->create_booking();
        $data = [
            'pdf' => null
        ];
        $this->post(route('booking.file.store', $booking->id), $data)
            ->assertStatus(422)
            ->assertJson(['errors' => [
                'pdf' => ['The pdf field is required.'],
            ]]) ;

    }

//    /** @test */
//    public function api_upload_booking_file_pdf_successfully()
//    {
//        $booking = $this->create_booking();
//        $booking_file =   $this->create_booking_file(['booking_id' => $booking->id, 'pdf' => $this->faker->image()]);
//        $this->post(route('booking.file.store', $booking->id), $booking_file->toArray())
//            ->assertStatus(201);
//
//    }

    /** @test */
    public function api_get_booking_file()
    {
        $booking = $this->create_booking();
        $this->create_booking_file(['booking_id' => $booking->id]);
        $res = $this->get(route('booking.file.index', $booking->id))
        ->assertStatus(200);
        $this->assertEquals($this->count($booking->bookingFiles), $this->count($res->content(['data'])));
        $this->assertDatabaseHas('booking_files', ['booking_id' => $booking->id]);

    }
    /** @test */
    public function api_delete_all_files_of_single_booking()
    {
        $booking = $this->create_booking();
        $this->create_booking_file(['booking_id' => $booking->id]);
        $this->delete(route('booking.file.delete', $booking->id));
        $this->assertDatabaseMissing('booking_files', ['booking_id' => $booking->id]);

    }
}
