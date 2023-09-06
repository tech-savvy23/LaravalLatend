<?php

use App\Models\Booking;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id')->index();
            $table->unsignedBigInteger('coupon_id')->nullable()->index();
            $table->unsignedBigInteger('partnerprice_id')->index();
            $table->unsignedDecimal('partner_price')->default(0);
            $table->unsignedDecimal('amount');
            $table->unsignedBigInteger('gst')->default(Booking::GST);
            $table->string('mode');
            $table->string('transaction_id')->nullable();
            $table->boolean('status')->default(false);
            $table->string('partner_type');
            $table->string('service')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
