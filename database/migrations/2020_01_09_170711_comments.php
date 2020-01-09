<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('comment');
            $table->integer('votes')->default(0);
            $table->integer('spam')->default(0);
            $table->integer('reply_id')->default(0);
            $table->string('contragent_id')->default(0);
            $table->integer('user_id');
            $table->timestamps();
        });
        Schema::create('comment_user_vote', function (Blueprint $table) {
            $table->integer('comment_id');
            $table->integer('user_id');
            $table->string('vote',11);
        });
        Schema::create('comment_spam', function (Blueprint $table) {
            $table->integer('comment_id');
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
