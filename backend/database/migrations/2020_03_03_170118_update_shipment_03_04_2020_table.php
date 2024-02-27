<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShipment03042020Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('shipments', function (Blueprint $table) {
            $table->boolean('booking_confirmed')->default(0);
            $table->boolean('arrival_notice_confirmed')->default(0);
            $table->boolean('freight_released_processed')->default(0);
            $table->boolean('customs_processed')->default(0);
            $table->boolean('DO_confirmed')->default(0);
            $table->boolean('freight_confirmed')->default(0);
            $table->boolean('customs_released')->default(0);
            $table->boolean('pending_refund')->default(0);
            $table->boolean('delivered')->default(0);
            $table->boolean('billed')->default(0);
            $table->boolean('cancelled')->default(0);
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
