<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{

    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->mediumText('img');
            $table->double('bazaarPrice', 8 ,2)->unsigned();
            $table->double('expoPrice', 8, 2)->unsigned();
            $table->string('barCode');
            $table->string('measure')->nullable();
            $table->integer('category_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('products');
    }
}
