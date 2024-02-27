<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveShipmentShipmentMilestoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('shipment_shipment_milestones', function (Blueprint $table) {
        //     $table->dropForeign('shipment_shipment_milestones_shipment_id_foreign');
        // });

        // Schema::dropIfExists('shipment_shipment_milestones');
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
