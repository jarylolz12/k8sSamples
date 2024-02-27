<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IsBookingEmailSentAtToShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            if(!Schema::hasColumn('shipments', 'is_booking_email_sent_at')) ;
            {
                $table->date('is_booking_email_sent_at')->nullable()->after('is_booking_email_sent');
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
            if(Schema::hasColumn('shipments', 'is_booking_email_sent_at')) ;
            {
                $table->dropColumn("is_booking_email_sent_at");
            }
        });
    }
}
