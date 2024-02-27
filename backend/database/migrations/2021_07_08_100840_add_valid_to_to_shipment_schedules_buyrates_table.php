<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidToToShipmentSchedulesBuyratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_schedules_buyrates', function (Blueprint $table) {
            //
            $table->date('valid_to')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_schedules_buyrates', function (Blueprint $table) {
            //
            $table->dropColumn('valid_to');
        });
    }
}
