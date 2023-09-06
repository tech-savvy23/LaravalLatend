<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BookingProduct;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookingProductTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_give_all_bookingproduct()
    {
        $this->create_bookingproduct();
        $this->getJson(route('bookingproduct.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_bookingproduct()
    {
        $bookingproduct = $this->create_bookingproduct();
        $this->getJson(route('bookingproduct.show', $bookingproduct->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_bookingproduct()
    {
        $booking        = $this->create_booking();
        $product        = $this->create_product();
        $bookingproduct = factory(BookingProduct::class)->make(['booking_id'=>$booking->id, 'product_id' => $product->id]);
        // \DB::connection()->enableQueryLog();
        $res            = $this->postJson(route('bookingproduct.store'), $bookingproduct->toArray())
                                ->assertStatus(200)->json();
        // dd(\DB::getQueryLog());
        $this->assertArrayHasKey('quantity', $res['data']);

        $this->assertDatabaseHas('booking_products', ['booking_id'=>$booking->id]);
    }

    /** @test */
    public function api_can_store_new_bookingproduct_only_once()
    {
        $booking        = $this->create_booking();
        $product        = $this->create_product();
        $bookingproduct = factory(BookingProduct::class)->make(['booking_id'=>$booking->id, 'product_id' => $product->id]);
        $res            = $this->postJson(route('bookingproduct.store'), $bookingproduct->toArray())->assertStatus(200);
        $this->assertDatabaseHas('booking_products', ['booking_id'=>$booking->id]);
        $res            = $this->postJson(route('bookingproduct.store'), $bookingproduct->toArray())->assertStatus(406);
    }

    /** @test */
    public function api_can_update_bookingproduct()
    {
        $bookingproduct = $this->create_bookingproduct();
        $this->putJson(route('bookingproduct.update', $bookingproduct->id), ['booking_id'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('booking_products', ['booking_id'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_bookingproduct()
    {
        $booking        = $this->create_booking();
        $product        = $this->create_product();
        $bookingproduct = $this->create_bookingproduct([
            'booking_id' => $booking->id,
            'product_id' => $product->id, ]);
        $this->deleteJson(route('bookingproduct.destroy', $booking->id), [
            'product_id' => $product->id,
        ])->assertStatus(204);
        $this->assertDatabaseMissing('booking_products', ['booking_id'=>$bookingproduct->booking_id]);
    }

    /** @test */
    public function api_can_give_products_of_booking_only()
    {
        $booking        = $this->create_booking();
        $products       = $this->create_product([], 5);
        $bookingproduct = $this->create_bookingproduct(['booking_id' => $booking->id, 'product_id' => $products[0]->id]);
        // \DB::connection()->enableQueryLog();
        $res            = $this->getJson(route('booking.products', $bookingproduct->id))->json();
        // dd(\DB::getQueryLog());

        $this->assertEquals(1, count($res));
    }

}
