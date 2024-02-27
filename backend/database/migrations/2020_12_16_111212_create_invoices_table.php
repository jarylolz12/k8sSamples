<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('qbo_customer_id')->nullable();
            $table->text('qbo_customer_name')->nullable();
            $table->text('invoice_num')->nullable();
            $table->text('qbo_bill_to_email')->nullable();
            $table->longText('qbo_bill_to_address')->nullable();
            $table->text('term')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->bigInteger('shipment_id')->unsigned()->nullable()->index('invoices_shipment_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
