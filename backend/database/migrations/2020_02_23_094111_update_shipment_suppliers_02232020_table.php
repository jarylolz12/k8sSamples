<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShipmentSuppliers02232020Table extends Migration
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
            $table->string('hbl_copy')->nullable();
            $table->string('packing_list')->nullable();
            $table->string('commercial_documents')->nullable();
            $table->string('commercial_invoice')->nullable();
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
