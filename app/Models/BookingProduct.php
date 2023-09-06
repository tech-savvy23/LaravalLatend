<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingProduct extends Model
{
    protected $fillable = ['booking_id', 'product_id', 'quantity','price'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function partnerTotalPrice() {
        return $this->sum(function ($product) {
            return $product->pivot->price * $product->pivot->quantity;
        });
    }
}
