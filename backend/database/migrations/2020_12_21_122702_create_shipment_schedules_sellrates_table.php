<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentSchedulesSellratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_schedules_sellrates', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id',3000)->nullable();
            $table->integer('service_id')->nullable();
            $table->integer('container_size_id')->nullable();
            $table->string('amount')->nullable();
            $table->integer('qty')->nullable();
            $table->string('schedule_unique_id',3000)->nullable();
            $table->bigInteger('shipment_schedules_id')->unsigned()->nullable()->index('shipment_schedules_sellrates_shipment_schedules_id_foreign');
            $table->foreign('shipment_schedules_id')->references('id')->on('shipment_schedules')->onDelete('CASCADE');
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
        Schema::dropIfExists('shipment_schedules_sellrates');
    }
}
