<?php

namespace Tests\Feature\User;

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
    public function admin_only_can_block_any_user()
    {
        $user = $this->create_user(['active' => true]);
        $this->post(route('user.block', $user->id))->assertSuccessful();
        $this->assertDatabaseHas('users', ['active'=>false]);
    }

    /** @test */
    public function admin_only_can_unblock_any_user()
    {
        $user = $this->create_user(['active' => false]);
        $this->post(route('user.unblock', $user->id))->assertSuccessful();
        $this->assertDatabaseHas('users', ['active'=>true]);
    }
}
