<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesLegsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_schedules_legs', function (Blueprint $table) {
            $table->id();
            $table->string('location_to')->nullable();
            $table->date('eta')->nullable();
            $table->string('mode')->nullable();
            $table->bigInteger('shipment_schedules_id')->unsigned()->nullable()->index('shipment_schedules_legs_shipment_schedules_id_foreign');
            $table->foreign('shipment_schedules_id')->references('id')->on('shipment_schedules')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules_legs');
    }
}
