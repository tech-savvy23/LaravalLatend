<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\Models\Address;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddressTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_user()
    {
        $user     = factory(User::class)->create();
        $Address  = factory(Address::class)->create(['user_id' => $user->id]);
        $this->assertInstanceOf(User::class, $Address->user);
    }
}
