<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnotherEmailFieldsToShiflOfficesTable extends Migration
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
            $table->json('customer_entry_notice_emails')->nullable();
            $table->json('carrier_eta_discrepancy_emails')->nullable();
            $table->json('discharged_discrepancy_emails')->nullable();
            $table->json('ata_discrepancy_emails')->nullable();

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
            $table->dropColumn('customer_entry_notice_emails');
            $table->dropColumn('carrier_eta_discrepancy_emails');
            $table->dropColumn('discharged_discrepancy_emails');
            $table->dropColumn('ata_discrepancy_emails');
        });
    }
}
