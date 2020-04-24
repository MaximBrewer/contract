<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAuctionsTable240420 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function ($table) {
            $table->enum('can_bet', ["yes", "no"])->default('yes');
            $table->enum('autosale', ["yes", "no"])->default('no');
            $table->integer('prepay')->default(50);
            $table->enum('mode', ["future", "price2day"])->default('price2day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auctions', function ($table) {
            $table->dropColumn('can_bet');
            $table->dropColumn('autosale');
            $table->dropColumn('mode');
        });
    }
}
