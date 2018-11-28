<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDevicesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imei')->unique();
            $table->string('name')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('battery')->nullable();
            $table->integer('phone')->nullable();
            $table->boolean('mode')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('UUID')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('devices');
    }
}
