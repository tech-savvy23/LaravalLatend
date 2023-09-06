<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Common\Otp;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OTPTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_generate_otp()
    {
        $booking = $this->create_booking();
        $otp     = Otp::generate($booking, $booking);
        $this->assertDatabaseHas('otps', ['otp'=>$otp]);
    }
}
