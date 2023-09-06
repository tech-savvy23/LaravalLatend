<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('address_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('area_number')->index();
            $table->unsignedBigInteger('area_id')->index();
            $table->dateTime('booking_time');
            $table->dateTime('contractor_time')->nullable();
            $table->unsignedInteger('status')->default(0);
            $table->boolean('otp_status')->default(false);
            $table->boolean('reschedule_status')->default(false);
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
        Schema::dropIfExists('bookings');
    }
}
