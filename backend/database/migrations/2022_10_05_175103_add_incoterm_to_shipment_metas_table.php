<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIncotermToShipmentMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_metas', function (Blueprint $table) {
            $table->string('incoterm');
            $table->string('notify_details_info');
            $table->boolean('notify_party')->nullable();
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
        });
    }
}
