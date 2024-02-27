<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->integer('qbo_bill_id')->nullable();
            $table->bigInteger('shipment_id')->unsigned()->nullable()->index('bills_shipment_id_foreign');
            $table->integer('qbo_vendor_id')->nullable();
            $table->integer('qbo_term_id')->nullable();
            $table->string('qbo_bill_num')->nullable();
            $table->string('qbo_vendor_name')->nullable();
            $table->string('qbo_mailing_address')->nullable();
            $table->date('qbo_bill_date')->nullable();
            $table->date('qbo_due_date')->nullable();
            $table->string('qbo_line',4000)->nullable();
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
        Schema::dropIfExists('bills');
    }
}
