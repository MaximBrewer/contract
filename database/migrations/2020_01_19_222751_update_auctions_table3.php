<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAuctionsTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function ($table) {
            $table->boolean('confirmed')->default(0);
            $table->boolean('started')->default(0);
            $table->boolean('finished')->default(0);
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
            $table->dropColumn('confirmed');
            $table->dropColumn('started');
            $table->dropColumn('finished');
        });
    }
}
