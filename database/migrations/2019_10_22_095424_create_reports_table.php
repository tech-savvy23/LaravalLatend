<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->unsignedBigInteger('checklist_id')->index();
            $table->unsignedBigInteger('checklist_type_id')->index()->nullable();
            $table->boolean('has_condition')->default(false);
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('parent_option_id')->default(0);
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
        Schema::dropIfExists('reports');
    }
}
