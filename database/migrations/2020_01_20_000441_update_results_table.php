<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bets', function ($table) {
            $table->timestamp('approved_volume')->nullable();
            $table->timestamp('approved_contract')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bets', function ($table) {
            $table->dropColumn('approved_volume');
            $table->dropColumn('approved_contract');
        });
    }
}
