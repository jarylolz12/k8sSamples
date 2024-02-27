<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsBookingEmailSentToShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            if(!Schema::hasColumn('shipments', 'is_booking_email_sent')) ;
            {
                $table->enum('is_booking_email_sent', array('0','1'))->default('0')->after('is_schedule_requested');
            }
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
            $table->dropColumn('is_booking_email_sent');
        });
    }
}
