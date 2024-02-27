<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsInvoiceOriginToInvoiceAttachmentsTable extends Migration
{

    public function up()
    {
        Schema::table('invoice_attachments', function (Blueprint $table) {
            $table->boolean('is_invoice_origin')->default(0)->after('include_on_send');
        });
    }

    public function down()
    {
        Schema::table('invoice_attachments', function (Blueprint $table) {
            $table->dropColumn('is_invoice_origin');
        });
    }
}
