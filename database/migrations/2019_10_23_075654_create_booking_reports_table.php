<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id')->index();
            $table->unsignedBigInteger('checklist_id')->index();
            $table->unsignedBigInteger('checklist_type_id')->index()->nullable();
            $table->unsignedBigInteger('report_id')->index();
            $table->unsignedBigInteger('selected_option_id')->index()->nullable();
            $table->unsignedBigInteger('multi_checklist_id')->index();
            $table->text('observation')->nullable();
            $table->string('result')->nullable();
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
        Schema::dropIfExists('booking_reports');
    }
}
