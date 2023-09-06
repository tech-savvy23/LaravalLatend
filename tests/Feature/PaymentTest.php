<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Coupon;
use App\Models\Payment;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PaymentTest extends TestCase
{
    use DatabaseMigrations;

    public function create_payment($args = [], $num = null)
    {
        return factory(Payment::class, $num)->create($args);
    }

    /** @test */
    public function api_can_give_all_payment()
    {
        $this->create_payment();
        $this->getJson(route('payment.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_all_payment_of_a_booking()
    {
        $this->authUser();
        $booking = $this->create_booking();
        $coupon  = factory(Coupon::class)->create();
        $payment = $this->create_payment(['booking_id' => $booking->id, 'coupon_id' => $coupon->id]);
        $res     = $this->getJson(route('booking.payment', $booking->id))->assertSuccessful()->json();
        $this->assertArrayHasKey('coupon', $res[0]);
    }

    /** @test */
    public function api_can_give_single_payment()
    {
        $payment = $this->create_payment();
        $this->getJson(route('payment.show', $payment->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_payment()
    {
        $payment = factory(Payment::class)->make(['booking_id'=>'Laravel']);
        $this->postJson(route('payment.store'), $payment->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Payments', ['booking_id'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_payment()
    {
        $payment = $this->create_payment();
        $this->putJson(route('payment.update', $payment->id), ['booking_id'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Payments', ['booking_id'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_payment()
    {
        $payment = $this->create_payment();
        $this->deleteJson(route('payment.destroy', $payment->id))->assertStatus(204);
        $this->assertDatabaseMissing('Payments', ['booking_id'=>$payment->booking_id]);
    }

    /** @test */
    public function api_can_payment_later()
    {
        $payment = $this->create_payment(['mode' => 'pay-later']);
        $data = [
            'transaction_id' => '1234565465ASDS',
        ];
        $this->patchJson(route('payment-later', $payment->id), $data)->assertOk();
        $this->assertDatabaseHas('payments', array_merge($data, ['status' => 1, 'id' => $payment->id]));
    }
}
