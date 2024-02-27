<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSellingRateFieldsToShipmentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('shipment_schedules', function (Blueprint $table) {
            //
             $table->integer('sellling_size_id')->nullable();
             $table->integer('selling_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_schedules', function (Blueprint $table) {
            //
        });
    }
}
