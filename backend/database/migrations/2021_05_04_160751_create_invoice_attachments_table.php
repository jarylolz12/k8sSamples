<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceAttachmentsTable extends Migration
{

    public function up()
    {
        Schema::create('invoice_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_id')->nullable();
            $table->bigInteger('qbo_invoice_id')->nullable();
            $table->bigInteger('shipment_id')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('file_name')->nullable();
            $table->text('file_path')->nullable();
            $table->boolean('include_on_send')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_attachments');
    }
}
