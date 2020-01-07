<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContragentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contragents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('federal_district')->nullable();
            $table->string('region')->nullable();
            $table->boolean('is_online');
            $table->boolean('active');
            $table->string('rating')->nullable();
            $table->string('holding')->nullable();
            $table->string('fio');
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
        Schema::dropIfExists('contragents');
    }
}
