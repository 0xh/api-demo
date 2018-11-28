<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymenttransactionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('transaction_type');
            $table->string('transaction_method');
            $table->string('amount');
            $table->string('last4');
            $table->string('brand');
            $table->string('exp_month');
            $table->string('exp_year');
            $table->string('token')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::drop('payment_transactions');
    }
}
