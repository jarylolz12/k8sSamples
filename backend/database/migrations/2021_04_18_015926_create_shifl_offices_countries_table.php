<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiflOfficesCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('shifl_offices_countries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shifl_office_id')
                  ->unsigned()
                  ->nullable();
            $table->bigInteger('country_id')
                  ->unsigned()
                  ->nullable();
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
        Schema::dropIfExists('shifl_offices_countries');
    }
}
