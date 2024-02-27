<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IndexRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('index_rates',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->date('data_date')->index();
            $table->string('location_from',200)->index()->nullable();
            $table->string('location_to',200)->index()->nullable();
            $table->string('terminal',200)->index()->nullable();
            $table->double('amount',8,2)->default(0);
            $table->string('container',55)->index()->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('index_rates');
    }
}
