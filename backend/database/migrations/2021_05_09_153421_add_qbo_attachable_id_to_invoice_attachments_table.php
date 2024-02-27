<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQboAttachableIdToInvoiceAttachmentsTable extends Migration
{

    public function up()
    {
        Schema::table('invoice_attachments', function (Blueprint $table) {
            $table->string('qbo_attachable_id')->nullable()->after('shipment_id');
        });
    }

    public function down()
    {
        Schema::table('invoice_attachments', function (Blueprint $table) {
            $table->dropColumn('qbo_attachable_id');
        });
    }
}
