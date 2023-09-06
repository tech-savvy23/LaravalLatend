<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PoliceVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function setup():void
    {
        parent::setup();
        $this->create_admin();
    }

    /** @test */
    public function admin_can_change_police_verification_of_partner()
    {
        $partner = $this->create_partner();
        $this->post(route('partner.police.verification', $partner->id))->assertSuccessful();

        $this->assertDatabaseHas('partners', ['police_verified_at'=>Carbon::now()]);
    }
}
