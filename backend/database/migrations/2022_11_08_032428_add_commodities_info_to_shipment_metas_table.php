<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommoditiesInfoToShipmentMetasTable extends Migration
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
            $table->string('commodities_info');
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
            $table->dropColumn('commodities_info');
        });
    }
}
