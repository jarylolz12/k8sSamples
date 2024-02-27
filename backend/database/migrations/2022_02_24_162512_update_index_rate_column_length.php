<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIndexRateColumnLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('index_rates', function (Blueprint $table) {
            $table->string('location_from',200)->change();
            $table->string('location_to',200)->change();
            $table->string('terminal',200)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('index_rates', function (Blueprint $table) {
            $table->string('location_from',200)->change();
            $table->string('location_to',200)->change();
            $table->string('terminal',200)->change();
        });
    }
}
