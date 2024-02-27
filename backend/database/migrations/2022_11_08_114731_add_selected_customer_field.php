<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelectedCustomerField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_report_schedules', function (Blueprint $table) {
            $table->bigInteger('selected_customer')->unsigned()->index('email_report_schedules_customer_id_foreign');
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
            $table->dropColumn('selected_customer');
        });
    }
}
