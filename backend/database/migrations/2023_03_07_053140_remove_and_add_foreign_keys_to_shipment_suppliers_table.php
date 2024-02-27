<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAndAddForeignKeysToShipmentSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_suppliers', function (Blueprint $table) {
            $table->dropForeign('shipment_suppliers_shipment_id_foreign');
            $table->dropForeign('shipment_suppliers_supplier_id_foreign');

            $table->foreign('shipment_id')->references('id')->on('shipments')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
            $table->dropForeign('shipment_suppliers_shipment_id_foreign');
            $table->dropForeign('shipment_suppliers_supplier_id_foreign');
        });
    }
}
