<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToShipmentMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_metas', function (Blueprint $table) {
            //
            $table->text('forwarders');
            $table->text('containers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_metas', function (Blueprint $table) {
            //
            $table->dropColumn('forwarders');
            $table->dropColumn('containers');
        });
    }
}
