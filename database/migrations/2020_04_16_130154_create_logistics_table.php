<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('gosznak');
            $table->string('coords');
            $table->text('description')->nullable();
            $table->integer('contragent_id');
            $table->integer('purpose_id');
            $table->integer('capacity_id');
            $table->integer('federal_district_id');
            $table->integer('region_id');
            $table->timestamp('available_from');
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
        Schema::dropIfExists('logistics');
    }
}
