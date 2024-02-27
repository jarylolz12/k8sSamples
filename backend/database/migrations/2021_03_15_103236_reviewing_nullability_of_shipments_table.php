<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReviewingNullabilityOfShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            //
            $table->string("suppliers_group", 4000)->nullable()->change();
            $table->string("containers_group", 4000)->nullable()->change();
            $table->text('custom_content')->nullable()->change();
            $table->text('suppliers_group_bookings')->nullable()->change();
            $table->text('containers_group_bookings')->nullable()->change();
            $table->text('schedules_group_bookings')->nullable()->change();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            //
        });
    }
}
