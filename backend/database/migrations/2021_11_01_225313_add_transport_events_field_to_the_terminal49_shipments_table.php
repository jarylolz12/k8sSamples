<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransportEventsFieldToTheTerminal49ShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terminal49_shipments', function (Blueprint $table) {
            //
            $table->text("transport_events")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terminal49_shipments', function (Blueprint $table) {
            //
            $table->dropColumn("transport_events");
        });
    }
}
