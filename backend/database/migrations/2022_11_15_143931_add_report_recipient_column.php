<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReportRecipientColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_report_schedules', function (Blueprint $table) {
            $table->json('report_recipients')->nullable(true);
            // $table->string('report_name')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_report_schedules', function (Blueprint $table) {
            $table->dropColumn('report_recipients');
            // $table->dropColumn('report_name');
        });
    }
}
