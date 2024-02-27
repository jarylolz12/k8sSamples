<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShipmentSuppliers02182020Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('shipment_suppliers', function (Blueprint $table) {
            $table->integer('cbm')->nullable();
            $table->integer('kg')->nullable();
            $table->string('hbl_num')->nullable();
            $table->string('product_description')->nullable();
            $table->integer('incoterm_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
