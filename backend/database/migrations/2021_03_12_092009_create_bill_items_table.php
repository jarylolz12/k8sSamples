<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillItemsTable extends Migration
{

    public function up()
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bill_id')->unsigned()->nullable()->index('bill_items_bill_id_foreign');
            $table->integer('qbo_item_value')->nullable();
            $table->integer('qbo_customer_id')->nullable();
            $table->string('qbo_item_name')->nullable();
            $table->string('qbo_customer_name')->nullable();
            $table->string('qbo_line_type')->nullable();
            $table->string('qbo_description')->nullable();
            $table->string('qbo_billable_status')->nullable();
            $table->decimal('qbo_amount',9,2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bill_items');
    }
}
