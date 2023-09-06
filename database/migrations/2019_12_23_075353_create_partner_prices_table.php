<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('state_id')->index();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->decimal('price');
            $table->string('type');
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
        Schema::dropIfExists('partner_prices');
    }
}
