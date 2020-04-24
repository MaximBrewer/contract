<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContragentsTable240420 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contragents', function ($table) {
            $table->boolean('requisites');    // кпп
            $table->string('kpp');    // кпп
            $table->string('rs');    // р/сч
            $table->string('bik');    // бик
            $table->string('bank');    // наименование банка 
            $table->enum('nds', ["w0", "w10", "w18", "w20"])->default('w10');    // ндс/без / ндс/включая ндс 18 / включая ндс 20 / включая ндс 10
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contragents', function ($table) {
            $table->dropColumn('requisites');
            $table->dropColumn('kpp');
            $table->dropColumn('rs');
            $table->dropColumn('bik');
            $table->dropColumn('bank');
            $table->dropColumn('nds');
        });
    }
}
