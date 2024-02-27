<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_emails', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
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
            $table->json('customer_entry_notice_emails')->nullable();
            $table->json('carrier_eta_discrepancy_emails')->nullable();
            $table->json('discharged_discrepancy_emails')->nullable();
            $table->json('ata_discrepancy_emails')->nullable();
            $table->json('manual_tracking_report_emails')->nullable();
            $table->json('container_count_mismatch_emails')->nullable();
            $table->bigInteger('office_id')->unsigned()->nullable()->index('offices_office_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_emails');
    }
}
