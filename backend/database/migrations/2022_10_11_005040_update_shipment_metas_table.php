<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShipmentMetasTable extends Migration
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
            $table->text('shipper_details_info')->nullable()->change();
            $table->text('consignee_details_info')->nullable()->change();
            $table->string('mode')->nullable()->change();
            $table->string('type')->nullable()->change();
            $table->string('incoterm')->nullable()->change();
            $table->string('notify_details_info')->nullable()->change();
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
        Schema::table('shipment_metas', function (Blueprint $table) {
            //
        });
    }
}
