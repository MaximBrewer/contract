<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('creditor_id');
            $table->integer('supplier_id');
            $table->text('description')->nullable();
            $table->enum('status', ['manufacturer', 'distributor', 'both'])->nullable();
            $table->string('orbits');
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
        Schema::dropIfExists('joints');
    }
}
