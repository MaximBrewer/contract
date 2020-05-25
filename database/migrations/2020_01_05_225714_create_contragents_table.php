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
            $table->string('title')->nullable();
            $table->string('federal_district')->nullable();
            $table->string('region')->nullable();
            $table->boolean('is_online')->default(0);
            $table->boolean('active')->default(1);
            $table->string('rating')->nullable();
            $table->string('holding')->nullable();
            $table->string('fio')->nullable();
            $table->string('inn', 16)->nullable();
            $table->text('legal_address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('requisites')->default(0);
            $table->string('kpp')->nullable();
            $table->string('rs')->nullable();
            $table->string('bik')->nullable();
            $table->string('bank')->nullable();
            $table->enum('nds', ["w0", "w10", "w18", "w20"])->default('w10');
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
