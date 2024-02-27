<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExclusiveFieldsToShiflOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shifl_offices', function (Blueprint $table) {
            //
            $table->json('manual_tracking_report_emails')->nullable();
            $table->json('container_count_mismatch_emails')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shifl_offices', function (Blueprint $table) {
            //
            $table->dropColumn('manual_tracking_report_emails');
            $table->dropColumn('container_count_mismatch_emails');
        });
    }
}
