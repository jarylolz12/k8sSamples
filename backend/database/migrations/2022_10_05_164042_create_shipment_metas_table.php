<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id');
            $table->integer('consignee_id');
            $table->integer('shipper_id');
            $table->text('shipper_details_info');
            $table->text('consignee_details_info');
            $table->integer('location_from');
            $table->integer('location_to');
            $table->string('mode');
            $table->string('type');
            $table->foreign('shipment_id')->references('id')->on('shipments');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_metas');
    }
}
