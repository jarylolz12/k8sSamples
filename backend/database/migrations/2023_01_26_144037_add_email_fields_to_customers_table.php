<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailFieldsToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
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
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
