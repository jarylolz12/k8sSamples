<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryIdToShiflOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shifl_offices', function (Blueprint $table) {
            //
            $table->bigInteger('country_id')
                  ->unsigned()
                  ->nullable();
            $table->foreign('country_id')
                  ->references('id')
                  ->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shifl_offices', function (Blueprint $table) {
            //
            $table->dropColumn('country_id');
        });
    }
}
