<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailsFieldsToShiflOfficesTable extends Migration
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
            $table->json('all_email_emails')->nullable();
            $table->json('booking_and_updated_emails')->nullable();
            $table->json('booking_confirmation_emails')->nullable();
            $table->json('agent_booking_updated_emails')->nullable();
            $table->json('agent_booking_confirmation_emails')->nullable();
            $table->json('departure_notice_emails')->nullable();
            $table->json('arrival_notice_emails')->nullable();
            $table->json('delivery_order_emails')->nullable();
            $table->json('eta_alert_emails')->nullable();
            $table->json('eta_alert_trucker_emails')->nullable();
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
            $table->dropColumn('all_email_emails');
            $table->dropColumn('booking_and_updated_emails');
            $table->dropColumn('booking_confirmation_emails');
            $table->dropColumn('agent_booking_updated_emails');
            $table->dropColumn('agent_booking_confirmation_emails');
            $table->dropColumn('departure_notice_emails');
            $table->dropColumn('arrival_notice_emails');
            $table->dropColumn('delivery_order_emails');
            $table->dropColumn('eta_alert_emails');
            $table->dropColumn('eta_alert_trucker_emails');

        });
    }
}
