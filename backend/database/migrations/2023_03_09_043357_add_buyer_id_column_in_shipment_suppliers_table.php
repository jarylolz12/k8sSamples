<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuyerIdColumnInShipmentSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_suppliers', function (Blueprint $table) {
            $table->bigInteger('buyer_id')->unsigned()->nullable()->after('supplier_id');
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
            $table->dropColumn('buyer_id');
        });
    }
}
