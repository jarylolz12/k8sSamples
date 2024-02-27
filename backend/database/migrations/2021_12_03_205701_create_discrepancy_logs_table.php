<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscrepancyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discrepancy_logs', function (Blueprint $table) {
            $table->id();
            $table->string("mbl_num");
            $table->enum("type", ["eta","ata","discharged"]);
            $table->date("system_date");
            $table->date("t49_date")->nullable();
            $table->text("data")->nullable();
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
        Schema::dropIfExists('discrepancy_logs');
    }
}
