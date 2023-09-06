<?php

namespace Tests\Feature\Partner;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlockUnblockTest extends TestCase
{
    use RefreshDatabase;

    public function setup():void
    {
        parent::setup();
        $this->create_admin();
    }

    /** @test */
    public function admin_only_can_block_any_partner()
    {
        $partner = $this->create_partner(['active' => true]);
        $this->post(route('partner.block', $partner->id))->assertSuccessful();
        $this->assertDatabaseHas('partners', ['active'=>false]);
    }

    /** @test */
    public function admin_only_can_unblock_any_partner()
    {
        $partner = $this->create_partner(['active' => false]);
        $this->post(route('partner.unblock', $partner->id))->assertSuccessful();
        $this->assertDatabaseHas('partners', ['active'=>true]);
    }
}
