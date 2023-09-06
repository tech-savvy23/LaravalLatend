<?php

namespace Tests\Feature;

use App\BookingColorCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingColorCodeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function create_booking_color_code()
    {
        $data = [
            'booking_report_id' => '1',
            'color_code_id' => '1',
        ];
        $response = $this->postJson(route('booking-color-codes.store'), $data);

        $response->assertStatus(201);
    }

    /** @test */
    public function update_booking_color_code()
    {
        $bookingColorCode = factory(BookingColorCode::class)->create();

        $data = [
            'booking_report_id' => 2,
            'color_code_id' => 2,
        ];
        $response = $this->patchJson(route('booking-color-codes.update', $bookingColorCode->id), $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('booking_color_codes', $data);
    }

    /** @test */
    public function delete_booking_color_code()
    {
        $bookingColorCode = factory(BookingColorCode::class)->create();
        $this->assertDatabaseHas('booking_color_codes', [
            'id' => $bookingColorCode->id
        ]);

        $response = $this->deleteJson(route('booking-color-codes.destroy', $bookingColorCode->id));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('booking_color_codes', [
            'id' => $bookingColorCode->id
        ]);
    }
}
