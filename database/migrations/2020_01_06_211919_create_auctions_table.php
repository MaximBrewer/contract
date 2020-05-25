<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->decimal('step');
            $table->boolean('approved')->default(0);
            $table->boolean('confirmed')->default(0);
            $table->boolean('started')->default(0);
            $table->boolean('finished')->default(0);
            $table->enum('can_bet', ["yes", "no"])->default('yes');
            $table->enum('autosale', ["yes", "no"])->default('no');
            $table->integer('prepay')->default(50);
            $table->enum('mode', ["future", "price2day"])->default('price2day');
            $table->string('picture')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auctions');
    }
}
