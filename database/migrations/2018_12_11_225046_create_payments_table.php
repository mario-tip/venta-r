<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->integer('order_id')->unsigned();
            $table->enum('method_payment',
            [
              'CASH' ,
              'INTERN_CREDIT' ,
              'DEBIT_CARD' ,'CREDIT_CARD' ,
              'TRANSFERENCE'
            ]);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
