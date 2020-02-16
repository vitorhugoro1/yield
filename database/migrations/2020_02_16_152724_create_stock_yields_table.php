<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockYieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_yields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('stock_id');
            $table->unsignedInteger('source_data_id');
            $table->string('income_type');
            $table->date('payed_at')->nullable();
            $table->date('negociated_at')->nullable();
            $table->decimal('amount', 8, 4);
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
        Schema::dropIfExists('stock_yields');
    }
}
