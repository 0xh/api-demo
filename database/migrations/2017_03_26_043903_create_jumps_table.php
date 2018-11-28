<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJumpsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jumps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id');
            $table->integer('amount');
            $table->integer('pet_id');
            $table->string('UUID');
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
        Schema::drop('jumps');
    }
}
