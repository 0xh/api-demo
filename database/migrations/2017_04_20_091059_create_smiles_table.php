<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSmilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('amount');
            $table->integer('device_id');
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
        Schema::drop('smiles');
    }
}
