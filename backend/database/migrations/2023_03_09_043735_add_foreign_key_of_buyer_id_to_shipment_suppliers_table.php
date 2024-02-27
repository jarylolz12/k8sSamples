<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyOfBuyerIdToShipmentSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_suppliers', function (Blueprint $table) {
            $table->foreign('buyer_id')->references('id')->on('buyer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_suppliers', function (Blueprint $table) {
            $table->dropForeign('shipment_suppliers_buyer_id_foreign');
        });
    }
}
