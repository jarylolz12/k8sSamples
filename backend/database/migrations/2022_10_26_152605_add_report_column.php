<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReportColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_report_schedules', function (Blueprint $table) {
            $table->text("report_columns")->nullable();
            $table->tinyInteger('report')->nullable();
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
            $table->dropColumn("report_columns");
            $table->dropColumn('report');
        });
    }
}
