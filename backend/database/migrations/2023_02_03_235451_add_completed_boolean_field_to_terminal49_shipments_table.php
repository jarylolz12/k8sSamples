<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletedBooleanFieldToTerminal49ShipmentsTable extends Migration
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
            $table->boolean('is_released')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->boolean('is_past_lfd_not_released')->default(0);
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
            $table->dropColumn('is_released');
            $table->dropColumn('is_completed');
            $table->dropColumn('is_past_lfd_not_released');
        });
    }
}
