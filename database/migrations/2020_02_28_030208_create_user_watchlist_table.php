<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWatchlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_watchlists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('stock_id');
            $table->unsignedInteger('user_id');
            $table->boolean('email')->nullable();
            $table->boolean('sms')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_watchlists');
    }
}
