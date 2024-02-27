<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLfdDailyReviewStatusAndNotesFieldToTerminal49ShipmentsTable extends Migration
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
            $table->text('lfd_daily_review_status')->nullable();
            $table->text('lfd_notes')->nullable();
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
            $table->dropColumn('lfd_daily_review_status');
            $table->dropColumn('lfd_notes');
        });
    }
}
