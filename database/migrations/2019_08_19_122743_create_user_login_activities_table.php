<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLoginActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('login_id')->unsigned()->index();
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->dateTime('last_activity');
            $table->string('timezone', 60);
            $table->foreign('login_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('user_login_activities');
    }
}
