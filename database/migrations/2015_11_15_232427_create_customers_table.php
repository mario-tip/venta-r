<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{

    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 10);
            $table->string('social_reason', 50);// IDEA: name of customers
            $table->string('rfc', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 80)->nullable();//->unique(); En birdman no es unico
            $table->string('street', 60)->nullable();
            $table->string('colony', 70)->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('cp')->nullable();
            $table->string('external_number');
            $table->string('internal_number')->nullable();
            $table->integer('company_id')->unsigned();
            $table->integer('sector_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('sector_id')->references('id')->on('sectors');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
