<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminal49ShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminal49_shipments', function (Blueprint $table) {
            $table->id();
            $table->string("mbl_num");
            $table->string("tr_id");
            $table->string("ts_id");
            $table->text("attributes")->nullable();
            $table->text("relationships")->nullable();
            $table->text("containers")->nullable();
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
        Schema::dropIfExists('terminal49_shipments');
    }
}
