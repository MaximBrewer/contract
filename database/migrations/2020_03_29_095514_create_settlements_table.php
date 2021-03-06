<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contragent_id');
            $table->bigInteger('bet_id')->nullable();
            $table->decimal('balance');
            $table->enum('type', ['debit', 'credit']);
            $table->enum('method', ['yandex', 'invoice']);
            $table->enum('status', ['processing', 'done']);
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
        Schema::dropIfExists('settlements');
    }
}
