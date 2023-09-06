<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingColorCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_color_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('booking_report_id')->nullable();
            $table->unsignedInteger('booking_device_id')->nullable();
            $table->unsignedInteger('color_code_id');
            $table->timestamps();
//            $table->foreign('booking_report_id')->on('booking_reports')->onDelete('cascade');
//            $table->foreign('color_code_id')->on('color_codes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_color_codes');
    }
}
