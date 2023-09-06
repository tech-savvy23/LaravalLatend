<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\Models\Partner;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FeedbackTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_booking()
    {
        $booking   = factory(\App\Models\Booking::class)->create();
        $Feedback  = factory(\App\Models\Feedback::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(\App\Models\Booking::class, $Feedback->booking);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user      = $this->create_user();
        $Feedback  = factory(\App\Models\Feedback::class)->create(['user_id' => $user->id]);
        $this->assertInstanceOf(User::class, $Feedback->user);
    }

    /** @test */
    public function it_belongs_to_partner()
    {
        $partner   = $this->create_partner();
        $Feedback  = factory(\App\Models\Feedback::class)->create(['partner_id' => $partner->id]);
        $this->assertInstanceOf(Partner::class, $Feedback->partner);
    }
}
