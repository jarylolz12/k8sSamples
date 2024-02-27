<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('bill_attachment', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bill_id')->nullable();
            $table->bigInteger('qbo_bill_id')->nullable();
            $table->bigInteger('shipment_id')->nullable();
            $table->string('filename')->nullable();
            $table->text('path')->nullable();
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
        Schema::dropIfExists('bill_attachment');
    }
}
