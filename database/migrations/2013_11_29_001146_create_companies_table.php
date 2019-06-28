<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->string('address');
            $table->string('phone');
            $table->string('rfc');
            $table->string('email_billing');
            $table->string('social_reason');
            $table->string('page_web');
            $table->string('state');
            $table->unsignedInteger('cedis_id');
            $table->string('colony');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
