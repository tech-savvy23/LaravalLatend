<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CouponTest extends TestCase
{
    use DatabaseMigrations;

    public function create_coupon($args = [], $num = null)
    {
        return factory(Coupon::class, $num)->create($args);
    }

    /** @test */
    public function api_can_verify_coupon_and_return_coupon_details()
    {
        $counpon = $this->create_coupon(['name'=>'welcome', 'active'=>true]);
        $res     = $this->postJson(route('coupon.verify'), ['coupon'=>'welcome'])->json();
        $this->assertArrayHasKey('discount', $res['data']);
    }

    /** @test */
    public function api_can_give_all_active_coupon()
    {
        $this->create_coupon(['active'=>true], 2);
        $this->create_coupon([], 2);
        $res = $this->getJson(route('coupon.index'))->assertOk()->assertJsonStructure(['data']);
        $this->assertEquals(2, count($res->json()['data']));
    }

    /** @test */
    public function api_can_give_all_coupon()
    {
        $this->create_coupon(['active'=>true], 2);
        $this->create_coupon([], 2);
        $res = $this->getJson(route('coupon.all'))->assertOk()->assertJsonStructure(['data']);
        $this->assertEquals(4, count($res->json()['data']));
    }

    /** @test */
    public function api_can_give_single_coupon()
    {
        $coupon = $this->create_coupon();
        $this->getJson(route('coupon.show', $coupon->id))->assertJsonStructure(['active']);
    }

    /** @test */
    public function api_can_store_new_coupon()
    {
        $coupon = factory(Coupon::class)->make(['name'=>'Laravel']);
        $this->postJson(route('coupon.store'), $coupon->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Coupons', ['name'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_coupon()
    {
        $coupon = $this->create_coupon();
        $this->putJson(route('coupon.update', $coupon->id), ['name'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Coupons', ['name'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_coupon()
    {
        $coupon = $this->create_coupon();
        $this->deleteJson(route('coupon.destroy', $coupon->id))->assertStatus(204);
        $this->assertDatabaseMissing('Coupons', ['name'=>$coupon->name]);
    }

    /** @test */
    public function api_can_activate_coupon()
    {
        $coupon = $this->create_coupon();
        $this->postJson(route('coupon.active', $coupon->id))->assertStatus(202);
        $this->assertDatabaseHas('coupons', ['active' => true]);
    }

    /** @test */
    public function api_can_deactivate_coupon()
    {
        $coupon = $this->create_coupon(['active' => true]);
        $this->deleteJson(route('coupon.active', $coupon->id))->assertStatus(204);
        $this->assertDatabaseHas('coupons', ['active' => false]);
    }
}
